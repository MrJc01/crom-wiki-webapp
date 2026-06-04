<?php

use yii\db\Migration;

class m260604_213531_create_welcome_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Criar tabela welcome_sliders
        $this->createTable('welcome_sliders', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'badge_text' => $this->string(32)->null(),
            'icon' => $this->string(64)->notNull()->defaultValue('👋'),
            'required_role' => $this->string(32)->defaultValue('all'), // 'all', 'membro', 'new_membro'
            'is_active' => $this->boolean()->defaultValue(true),
            'slides_json' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // 2. Inserir dados padrão de boas-vindas
        $defaultSlides = [
            [
                'title' => 'Bem-vindo ao Portal CROM',
                'description' => 'Este é o seu novo ecossistema de desenvolvimento descentralizado. Explore ferramentas e documentação integrada de forma local-first.',
                'image_url' => '👋',
                'gradiente' => 'from-sky-500/20 to-indigo-500/0'
            ],
            [
                'title' => 'Soberania Digital & Local-First',
                'description' => 'Todos os seus dados de wiki e configurações são locais e sincronizados via GitOps. Autonomia total sobre sua governança.',
                'image_url' => '🔒',
                'gradiente' => 'from-emerald-500/20 to-teal-500/0'
            ],
            [
                'title' => 'Inteligência Artificial Integrada',
                'description' => 'Acesse modelos de linguagem avançados de forma segura e privada diretamente da aba CromIA.',
                'image_url' => '🤖',
                'gradiente' => 'from-purple-500/20 to-rose-500/0'
            ]
        ];

        $this->insert('welcome_sliders', [
            'title' => 'Bem-vindo',
            'badge_text' => 'Novo',
            'icon' => '👋',
            'required_role' => 'new_membro',
            'is_active' => true,
            'slides_json' => json_encode($defaultSlides, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        // 3. Registrar o novo módulo na tabela core_modules
        $this->insert('core_modules', [
            'id' => 'welcome',
            'name' => 'Boas-vindas & Badges',
            'entry_point' => 'welcome/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.05 4.575a1.5 1.5 0 00-1.8 0L2.25 9.075M12 3v18M21.75 9.075l-6-4.5a1.5 1.5 0 00-1.8 0v15a1.5 1.5 0 001.8 0l6-4.5a1.5 1.5 0 000-2.4z" /></svg>',
            'sort_order' => 15,
            'is_active' => true,
            'required_permission' => 'admin-access',
            'category' => 'Segurança',
            'description' => 'Gerenciamento de badges flutuantes no banner e fluxos de sliders de boas-vindas para novos membros.'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('core_modules', ['id' => 'welcome']);
        $this->dropTable('welcome_sliders');
    }
}
