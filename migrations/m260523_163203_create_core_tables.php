<?php

use yii\db\Migration;

class m260523_163203_create_core_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Tabela core_users
        $this->createTable('core_users', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'access_token' => $this->string()->unique(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // 2. Tabela core_modules
        $this->createTable('core_modules', [
            'id' => $this->string(64)->notNull(),
            'name' => $this->string()->notNull(),
            'entry_point' => $this->string()->notNull(),
            'icon' => $this->text(),
            'sort_order' => $this->integer()->defaultValue(0),
            'is_active' => $this->boolean()->defaultValue(true),
            'PRIMARY KEY (id)',
        ]);

        // 3. Tabela core_session_status
        $this->createTable('core_session_status', [
            'user_id' => $this->integer()->notNull(),
            'last_activity' => $this->integer()->notNull(),
            'is_online' => $this->boolean()->defaultValue(true),
            'PRIMARY KEY (user_id)',
        ]);

        // Inserir um administrador inicial (senha: admin123)
        $security = Yii::$app->security;
        $passwordHash = $security->generatePasswordHash('admin123');
        $time = time();

        $this->insert('core_users', [
            'username' => 'admin',
            'password_hash' => $passwordHash,
            'access_token' => $security->generateRandomString(32),
            'created_at' => $time,
            'updated_at' => $time,
        ]);

        // Inserir o módulo wiki como padrão inicial
        $this->insert('core_modules', [
            'id' => 'app-wiki',
            'name' => 'Wiki Interna',
            'entry_point' => 'wiki/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('core_session_status');
        $this->dropTable('core_modules');
        $this->dropTable('core_users');
    }
}
