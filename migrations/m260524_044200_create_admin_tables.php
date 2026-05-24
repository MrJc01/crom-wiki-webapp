<?php

use yii\db\Migration;

/**
 * Class m260524_044200_create_admin_tables
 */
class m260524_044200_create_admin_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Criar tabela core_settings
        $this->createTable('core_settings', [
            'key' => $this->string(64)->notNull(),
            'value' => $this->text(),
            'PRIMARY KEY (key)',
        ]);

        // Popular com as chaves padrões (mockadas) do Dashboard
        $settings = [
            'portal_title' => 'CROM',
            'portal_subtitle' => 'Developer Program',
            'dashboard_badge' => 'Soberania',
            'dashboard_title' => 'CROM',
            'dashboard_desc' => 'Crie e colabore em documentações locais em Markdown com autonomia radical e controle de governança direto na base.',
            'dashboard_btn_text' => 'Consultar Documentos Internos',
            'dashboard_btn_tab' => 'page_crud',
        ];

        foreach ($settings as $key => $value) {
            $this->insert('core_settings', [
                'key' => $key,
                'value' => $value
            ]);
        }

        // 2. Adicionar coluna status na tabela core_users (1 = Ativo, 0 = Bloqueado)
        $this->addColumn('core_users', 'status', $this->integer()->defaultValue(1));

        // 3. Criar permissão admin-access no auth_item (Yii2 RBAC)
        $time = time();
        $this->insert('auth_item', [
            'name' => 'admin-access',
            'type' => 2, // Permission
            'description' => 'Acesso completo ao Painel Administrativo',
            'created_at' => $time,
            'updated_at' => $time,
        ]);

        // Associar a permissão ao usuário de ID 1 (admin padrão)
        $this->insert('auth_assignment', [
            'item_name' => 'admin-access',
            'user_id' => '1',
            'created_at' => $time,
        ]);

        // Registrar o módulo admin na tabela core_modules
        $this->insert('core_modules', [
            'id' => 'admin',
            'name' => 'Painel Administrativo',
            'entry_point' => 'admin/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
            'sort_order' => 10,
            'is_active' => true,
            'required_permission' => 'admin-access'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('core_modules', ['id' => 'admin']);
        $this->delete('auth_assignment', ['item_name' => 'admin-access', 'user_id' => '1']);
        $this->delete('auth_item', ['name' => 'admin-access']);
        $this->dropColumn('core_users', 'status');
        $this->dropTable('core_settings');
    }
}
