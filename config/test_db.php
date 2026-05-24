<?php

$db = require __DIR__ . '/db.php';
// Banco de dados SQLite isolado para testes
$db['dsn'] = 'sqlite:@app/data/core_test.db';

return $db;
