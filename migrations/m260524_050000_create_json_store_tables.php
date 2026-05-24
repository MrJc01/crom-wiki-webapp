<?php

use yii\db\Migration;

/**
 * Módulo JSON Store — Criação das tabelas de persistência, registro do módulo
 * e inserção de dados mockados para ambiente de desenvolvimento.
 */
class m260524_050000_create_json_store_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Tabela principal de endpoints JSON
        $this->createTable('json_store_endpoints', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull()->unique(),
            'name' => $this->string(255)->notNull(),
            'json_content' => $this->text()->notNull()->defaultValue('{}'),
            'is_public' => $this->boolean()->defaultValue(1),
            'created_by' => $this->string(255)->notNull(),
            'category' => $this->string(100)->notNull()->defaultValue('Geral'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-json_store_endpoints-slug', 'json_store_endpoints', 'slug');

        // 2. Tabela de tokens de acesso (1:N com endpoint)
        $this->createTable('json_store_tokens', [
            'id' => $this->primaryKey(),
            'endpoint_id' => $this->integer()->notNull(),
            'token_hash' => $this->string(64)->notNull(),
            'label' => $this->string(100)->defaultValue('default'),
            'created_at' => $this->integer()->notNull(),
            'FOREIGN KEY (endpoint_id) REFERENCES json_store_endpoints(id) ON DELETE CASCADE',
        ]);

        $this->createIndex('idx-json_store_tokens-endpoint', 'json_store_tokens', 'endpoint_id');
        $this->createIndex('idx-json_store_tokens-hash', 'json_store_tokens', 'token_hash');

        // 3. Tabela associativa N:N de administradores
        $this->createTable('json_store_admins', [
            'endpoint_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'PRIMARY KEY (endpoint_id, user_id)',
            'FOREIGN KEY (endpoint_id) REFERENCES json_store_endpoints(id) ON DELETE CASCADE',
            'FOREIGN KEY (user_id) REFERENCES core_users(id) ON DELETE CASCADE',
        ]);

        // 4. Tabela de logs de acesso à API
        $this->createTable('json_store_access_logs', [
            'id' => $this->primaryKey(),
            'endpoint_id' => $this->integer()->notNull(),
            'ip_address' => $this->string(45)->null(),
            'user_agent' => $this->string(500)->null(),
            'accessed_at' => $this->integer()->notNull(),
            'FOREIGN KEY (endpoint_id) REFERENCES json_store_endpoints(id) ON DELETE CASCADE',
        ]);

        $this->createIndex('idx-json_store_access_logs-endpoint-time', 'json_store_access_logs', ['endpoint_id', 'accessed_at']);

        // 5. Registrar módulo na tabela core_modules
        $this->insert('core_modules', [
            'id' => 'json_store',
            'name' => 'JSON Store',
            'entry_point' => 'json_store/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" /></svg>',
            'sort_order' => 3,
            'is_active' => true,
            'required_permission' => 'access-wiki',
        ]);

        // 6. Dados mockados de exemplo
        $time = time();

        // Endpoint público
        $this->insert('json_store_endpoints', [
            'slug' => 'config-app-mobile',
            'name' => 'Configuração App Mobile',
            'json_content' => json_encode([
                'app_name' => 'CROM Mobile',
                'version' => '2.1.0',
                'maintenance_mode' => false,
                'api_base_url' => 'https://api.crom.dev/v1',
                'features' => [
                    'dark_mode' => true,
                    'push_notifications' => true,
                    'offline_mode' => false,
                ],
                'min_supported_version' => '1.8.0',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'is_public' => 1,
            'created_by' => 'admin',
            'category' => 'Mobile',
            'created_at' => $time,
            'updated_at' => $time,
        ]);

        // Endpoint privado (com token)
        $this->insert('json_store_endpoints', [
            'slug' => 'webhooks-discord',
            'name' => 'Webhooks Discord Internos',
            'json_content' => json_encode([
                'webhooks' => [
                    [
                        'name' => 'deploy-notifications',
                        'url' => 'https://discord.com/api/webhooks/EXAMPLE/TOKEN',
                        'channel' => '#deploys',
                    ],
                    [
                        'name' => 'error-alerts',
                        'url' => 'https://discord.com/api/webhooks/EXAMPLE2/TOKEN2',
                        'channel' => '#alerts',
                    ],
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'is_public' => 0,
            'created_by' => 'admin',
            'category' => 'Infraestrutura',
            'created_at' => $time,
            'updated_at' => $time,
        ]);

        // Token de acesso para o endpoint privado (hash de "crom_test_token_123")
        $this->insert('json_store_tokens', [
            'endpoint_id' => 2, // webhooks-discord
            'token_hash' => hash('sha256', 'crom_test_token_123'),
            'label' => 'Token de Teste Dev',
            'created_at' => $time,
        ]);

        // Admin do endpoint público
        $this->insert('json_store_admins', [
            'endpoint_id' => 1,
            'user_id' => 1,
        ]);

        // Admin do endpoint privado
        $this->insert('json_store_admins', [
            'endpoint_id' => 2,
            'user_id' => 1,
        ]);

        // Logs mockados de acesso
        for ($i = 0; $i < 15; $i++) {
            $this->insert('json_store_access_logs', [
                'endpoint_id' => 1,
                'ip_address' => '192.168.1.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (MockBot/' . $i . ')',
                'accessed_at' => $time - rand(0, 172800), // Últimas 48h
            ]);
        }

        for ($i = 0; $i < 5; $i++) {
            $this->insert('json_store_access_logs', [
                'endpoint_id' => 2,
                'ip_address' => '10.0.0.' . rand(1, 254),
                'user_agent' => 'curl/7.88.1',
                'accessed_at' => $time - rand(0, 86400), // Últimas 24h
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('core_modules', ['id' => 'json_store']);
        $this->dropTable('json_store_access_logs');
        $this->dropTable('json_store_admins');
        $this->dropTable('json_store_tokens');
        $this->dropIndex('idx-json_store_endpoints-slug', 'json_store_endpoints');
        $this->dropTable('json_store_endpoints');
    }
}
