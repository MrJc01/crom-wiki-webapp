<?php

return [
    'class' => \yii\db\Connection::class,
    'dsn' => getenv('DB_DSN') ?: 'sqlite:@app/data/core.db',
    'charset' => 'utf8',
    'on afterOpen' => function($event) {
        $event->sender->createCommand("PRAGMA journal_mode=WAL;")->execute();
        $event->sender->createCommand("PRAGMA busy_timeout=5000;")->execute();
    },

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
