<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use phpseclib3\Net\SSH2;

if ($argc < 4) {
    echo "Uso: php test_ssh.php <host> <user> <pass>\n";
    exit(1);
}

$host = $argv[1];
$user = $argv[2];
$pass = $argv[3];

echo "Iniciando conexao SSH para {$host} com usuario {$user}...\n";

try {
    $ssh = new SSH2($host, 22);
    $ssh->setTimeout(15);
    
    echo "Conexão de socket aberta. Tentando autenticar...\n";
    if ($ssh->login($user, $pass)) {
        echo "Sucesso! Autenticado com exito.\n";
        $ssh->enablePTY();
        echo "PTY habilitado. Lendo banner inicial...\n";
        echo $ssh->read();
        echo "\nDesconectando...\n";
    } else {
        echo "Falha na autenticação SSH com as credenciais informadas.\n";
    }
} catch (\Throwable $e) {
    echo "Erro capturado: " . get_class($e) . "\n";
    echo "Mensagem: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
