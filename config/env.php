<?php

declare(strict_types=1);

/**
 * Loader ultraleve e autônomo de variáveis de ambiente (.env) para o Yii2.
 * Evita o overhead de carregar pacotes adicionais do Composer durante a inicialização.
 */
(function () {
    $envPath = dirname(__DIR__) . '/.env';
    if (file_exists($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Ignora linhas vazias ou comentários
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }
            
            // Faz o split no primeiro sinal de '='
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                // Remove aspas simples ou duplas das extremidades do valor
                $value = trim($value, "\"'");
                
                // Define no ambiente do PHP se ainda não estiver explicitamente definido
                if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                    putenv("{$name}={$value}");
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }
})();

// Define as constantes de controle de ambiente do Yii2 baseadas no arquivo .env
// Fallback seguro: se não houver variáveis, assume modo de Produção Otimizado.
$envDebug = getenv('YII_DEBUG');
defined('YII_DEBUG') or define('YII_DEBUG', ($envDebug === '1' || $envDebug === 'true' || $envDebug === 'YII_DEBUG'));
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ?: 'prod');
