<?php

declare(strict_types=1);

namespace app\modules\terminal\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use phpseclib3\Net\SSH2;

class DefaultController extends Controller
{
    // Desabilitar validação CSRF para requisições do terminal via POST
    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['access-terminal'], // Exige a permissão RBAC access-terminal
                    ],
                ],
            ],
        ];
    }

    /**
     * Interceptador para ignorar layout no HTMX
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ($action->id === 'index') {
                if (Yii::$app->request->headers->has('HX-Request')) {
                    $this->layout = false;
                } else {
                    $this->layout = '@app/views/layouts/main';
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Renderiza a página do Terminal SSH
     */
    public function actionIndex(): string
    {
        $serversEnv = getenv('TERMINAL_SERVERS');
        $servers = [];
        if ($serversEnv) {
            foreach (explode(',', $serversEnv) as $pair) {
                $parts = explode(':', $pair, 2);
                if (count($parts) === 2) {
                    $servers[$parts[0]] = $parts[1];
                }
            }
        }
        // Fallback or local terminal
        $servers['localhost'] = 'localhost (Servidor Local)';

        return $this->render('index', [
            'servers' => $servers
        ]);
    }

    /**
     * Conexão de Stream persistente (SSE - Server-Sent Events)
     */
    public function actionStream()
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        
        // Configura cabeçalhos através do objeto de resposta do Yii2 para compatibilidade total
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');
        
        // Evita bufferings do servidor
        if (function_exists('ob_end_clean')) {
            @ob_end_clean();
        }
        
        // Envia cabeçalhos de forma limpa antes do início da transmissão
        $response->send();

        $host = Yii::$app->request->get('host');
        $user = Yii::$app->request->get('user');
        $pass = Yii::$app->request->get('pass');
        $id = Yii::$app->request->get('id');

        if (empty($host) || empty($user) || empty($id)) {
            echo "data: " . json_encode(['error' => 'Parâmetros de conexão inválidos.']) . "\n\n";
            flush();
            return;
        }

        // 1. Limitar a quantidade de conexões simultâneas ao terminal para evitar exaustão do PHP-FPM
        $maxConnections = 3; // Limite prudente reservando workers para o resto do site
        $lockPattern = Yii::getAlias('@runtime/term_active_*.lock');
        $lockFiles = glob($lockPattern);
        
        $activeCount = 0;
        foreach ($lockFiles as $file) {
            $pid = (int)@file_get_contents($file);
            // Verifica se o PID está ativo no sistema operacional
            if ($pid > 0 && file_exists("/proc/$pid")) {
                $activeCount++;
            } else {
                // Remove lock file órfão de processo encerrado abruptamente
                @unlink($file);
            }
        }

        if ($activeCount >= $maxConnections) {
            echo "data: " . json_encode(['error' => 'O terminal atingiu o limite de conexões simultâneas de usuários. Por favor, aguarde alguns instantes e tente novamente.']) . "\n\n";
            flush();
            Yii::$app->end();
            return;
        }

        // Cria o lock file com o Process ID (PID) do worker PHP atual
        $myPid = getmypid();
        $lockFile = Yii::getAlias('@runtime/term_active_' . $id . '.lock');
        @file_put_contents($lockFile, (string)$myPid);

        // Determina o arquivo de atividade para monitorar inatividade do frontend
        $activityFile = Yii::getAlias('@runtime/term_activity_' . $id . '.txt');
        @file_put_contents($activityFile, (string)time());

        // Determina o arquivo de buffer de entrada
        $inputBufferFile = Yii::getAlias('@runtime/ssh_input_' . $id . '.txt');
        if (file_exists($inputBufferFile)) {
            @unlink($inputBufferFile);
        }

        try {
            // Inicializa a conexão SSH
            $ssh = new SSH2($host, 22);
            $ssh->setTimeout(10); // Timeout generoso para fase de negociação e login

            if (!$ssh->login($user, $pass)) {
                echo "data: " . json_encode(['error' => 'Falha na autenticação SSH. Verifique o usuário e a chave de acesso.']) . "\n\n";
                flush();
                return;
            }

            // Ativa o Pseudo-Terminal (PTY) para a shell interativa
            $ssh->enablePTY();

            // Realiza a leitura inicial (abertura implícita do shell) usando o timeout longo (10s)
            $initialOutput = $ssh->read();
            if ($initialOutput === false) {
                $initialOutput = '';
            }

            echo "data: " . json_encode(['status' => 'connected', 'msg' => "\r\n=== Conectado com sucesso ao servidor {$host} via SSH ===\r\n\r\n" . $initialOutput]) . "\n\n";
            flush();

            // Configura o timeout ultra baixo de 20ms apenas para leitura não-bloqueante durante o loop de stream
            $ssh->setTimeout(0.02);

            $lastPing = time();

            // Loop infinito de streaming bidirecional
            while (true) {
                // Se a conexão foi fechada pelo cliente, encerra o loop para evitar processos órfãos
                if (connection_aborted()) {
                    break;
                }

                // Verifica timeout de atividade do frontend (caso cliente suma ou pare de mandar batimento cardíaco)
                $lastActivity = (int)@file_get_contents($activityFile);
                if ($lastActivity > 0 && (time() - $lastActivity) > 45) {
                    Yii::warning("Sessão terminal {$id} encerrada por inatividade de ping do frontend.", 'terminal');
                    break;
                }

                // Envia ping de batimento cardíaco a cada 1 segundo para detecção ativa de desconexão do cliente
                $now = time();
                if ($now - $lastPing >= 1) {
                    echo ": ping\n\n";
                    flush();
                    $lastPing = $now;
                }

                // 1. Ler saída do SSH
                $output = $ssh->read();
                if ($output !== false && $output !== '') {
                    echo "data: " . json_encode(['output' => base64_encode($output)]) . "\n\n";
                    flush();
                }

                // 2. Ler entrada do buffer e escrever no SSH
                if (file_exists($inputBufferFile)) {
                    $input = file_get_contents($inputBufferFile);
                    if ($input !== false && $input !== '') {
                        // Deleta o arquivo de buffer de forma atômica para a próxima escrita
                        @unlink($inputBufferFile);
                        $ssh->write($input);
                    }
                }

                // Dorme 20ms para aliviar uso de CPU
                usleep(20000);
            }

        } catch (\Throwable $e) {
            $msg = $e->getMessage();
            // Log do erro real no framework Yii para auditoria interna
            Yii::error("Erro real na conexão SSH via SSE: " . $msg . "\n" . $e->getTraceAsString(), 'terminal');

            // Converte avisos internos do phpseclib causados por sockets fechadas ou falhas de pacotes em avisos claros de rede
            if (strpos($msg, 'Undefined array key') !== false || strpos($msg, 'Connection closed') !== false) {
                $msg = 'Conexão rejeitada ou encerrada de forma prematura pelo servidor SSH de destino. Verifique se o usuário/senha estão corretos ou se o firewall bloqueou o IP do servidor web.';
            }
            echo "data: " . json_encode(['error' => 'Erro na conexão SSH: ' . $msg]) . "\n\n";
            flush();
        } finally {
            // Garante a remoção do lock file para liberar o slot ao fechar a conexão
            if (isset($lockFile) && file_exists($lockFile)) {
                @unlink($lockFile);
            }
            // Limpa o arquivo de atividade correspondente
            if (isset($activityFile) && file_exists($activityFile)) {
                @unlink($activityFile);
            }
        }

        // Encerra a execução do Yii2 de forma limpa para evitar HeadersAlreadySentException
        Yii::$app->end();
    }

    /**
     * Endpoint para gravar entrada do usuário no buffer
     */
    public function actionWrite()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $data = Yii::$app->request->post('data');

        if (empty($id)) {
            return ['status' => 'error', 'message' => 'ID da sessão inválido.'];
        }

        // Atualiza o timestamp de atividade do frontend (provando que o cliente está vivo)
        $activityFile = Yii::getAlias('@runtime/term_activity_' . $id . '.txt');
        @file_put_contents($activityFile, (string)time());

        // Se data for nulo ou vazio, é apenas o ping de batimento cardíaco periódico do frontend
        if ($data === null || $data === '') {
            return ['status' => 'success'];
        }

        $inputBufferFile = Yii::getAlias('@runtime/ssh_input_' . $id . '.txt');

        // Adiciona a entrada ao final do arquivo de buffer
        $fp = fopen($inputBufferFile, 'a');
        if ($fp) {
            fwrite($fp, $data);
            fclose($fp);
            return ['status' => 'success'];
        }

        return ['status' => 'error', 'message' => 'Falha ao escrever no buffer de entrada.'];
    }
}
