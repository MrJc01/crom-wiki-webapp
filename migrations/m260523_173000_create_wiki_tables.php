<?php

use yii\db\Migration;

class m260523_173000_create_wiki_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Tabela wiki_github_auth
        $this->createTable('wiki_github_auth', [
            'user_id' => $this->integer()->notNull(),
            'gh_user_id' => $this->integer()->notNull()->unique(),
            'gh_username' => $this->string(255)->notNull(),
            'access_token' => $this->text()->notNull(),
            'refresh_token' => $this->text()->notNull(),
            'expires_at' => $this->integer()->notNull(),
            'PRIMARY KEY (user_id)',
        ]);

        // 2. Tabela wiki_pages_cache
        $this->createTable('wiki_pages_cache', [
            'path' => $this->string(255)->notNull(),
            'sha' => $this->string(64)->notNull(),
            'title' => $this->string(255)->notNull(),
            'content' => $this->text()->notNull(),
            'last_synced_at' => $this->integer()->notNull(),
            'PRIMARY KEY (path)',
        ]);

        // 3. Tabela wiki_live_sessions
        $this->createTable('wiki_live_sessions', [
            'id' => $this->string(64)->notNull(),
            'path' => $this->string(255)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->string(64)->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'PRIMARY KEY (id)',
        ]);

        // Criar índice secundário para melhorar a velocidade das checagens de Lock concorrente
        $this->createIndex('idx-wiki_live_sessions-path', 'wiki_live_sessions', 'path');

        // 4. Corrigir o ID e rota do módulo na tabela core_modules de 'app-wiki' para 'wiki'
        $this->update('core_modules', [
            'id' => 'wiki',
            'entry_point' => 'wiki/default/index'
        ], ['id' => 'app-wiki']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Reverter a mudança do ID do módulo
        $this->update('core_modules', [
            'id' => 'app-wiki',
            'entry_point' => 'wiki/default/index'
        ], ['id' => 'wiki']);

        // Dropar tabelas da wiki
        $this->dropIndex('idx-wiki_live_sessions-path', 'wiki_live_sessions');
        $this->dropTable('wiki_live_sessions');
        $this->dropTable('wiki_pages_cache');
        $this->dropTable('wiki_github_auth');
    }
}
