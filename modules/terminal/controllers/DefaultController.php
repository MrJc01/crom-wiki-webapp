<?php

declare(strict_types=1);

namespace app\modules\terminal\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use phpseclib3\Net\SSH2;

class DefaultController extends Controller
{
    // Desabilitar validação CSRF para requisições do terminal via POST
    public $enableCsrfValidation = false;

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
        $servers = [
            'crom.me' => 'crom.me (Guardiões)',
            'vps1.crom.me' => 'vps1.crom.me (Pilares)',
            'vps2.crom.me' => 'vps2.crom.me (Forja)',
            'localhost' => 'localhost (Servidor Local)',
        ];

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

            echo "data: " . json_encode(['status' => 'connected', 'msg' => "\r\n=== Conectado com sucesso ao servidor {$host} via SSH ===\r\n\r\n"]) . "\n\n";
            flush();

            // Configura o timeout ultra baixo de 20ms apenas para leitura não-bloqueante durante o loop de stream
            $ssh->setTimeout(0.02);

            // Loop infinito de streaming bidirecional
            while (true) {
                // Se a conexão foi fechada pelo cliente, encerra o loop para evitar processos órfãos
                if (connection_aborted()) {
                    break;
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
            // Converte avisos internos do phpseclib causados por sockets fechadas ou falhas de pacotes em avisos claros de rede
            if (strpos($msg, 'Undefined array key') !== false || strpos($msg, 'Connection closed') !== false) {
                $msg = 'Conexão rejeitada ou encerrada de forma prematura pelo servidor SSH de destino. Verifique se o usuário/senha estão corretos ou se o firewall bloqueou o IP do servidor web.';
            }
            echo "data: " . json_encode(['error' => 'Erro na conexão SSH: ' . $msg]) . "\n\n";
            flush();
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

        if (empty($id) || $data === null || $data === '') {
            return ['status' => 'error', 'message' => 'Parâmetros insuficientes.'];
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
