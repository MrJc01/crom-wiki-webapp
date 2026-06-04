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
                'title' => 'Bem-vindo à CROM',
                'description' => '"Soberania não se pede, constrói-se." Você agora faz parte de um ecossistema focado em autonomia digital e descentralização.',
                'image_url' => '👋',
                'gradiente' => 'from-sky-500/20 to-indigo-500/0'
            ],
            [
                'title' => 'Estrutura Horizontal de Camadas',
                'description' => 'Não temos chefes ou gerentes. Funcionamos em 3 camadas de evolução contínua baseadas em confiança e histórico auditável: a Forja (Camada 1), os Pilares (Camada 2) e os Guardiões (Camada 3).',
                'image_url' => '🏛️',
                'gradiente' => 'from-indigo-500/20 to-purple-500/0'
            ],
            [
                'title' => 'A Forja',
                'description' => 'Sua jornada começa aqui. Explore ferramentas, crie protótipos em nossa VPS dedicada com Podman e mostre seu impacto auditável no Git para ascender ao nível Pilar.',
                'image_url' => '🔥',
                'gradiente' => 'from-amber-500/20 to-orange-500/0'
            ],
            [
                'title' => 'Contrato de Cuidado & Vesting',
                'description' => 'O valor que você gera nunca é apagado. O Vesting de Impacto garante recompensas residuais futuras e reconhecimento perpétuo do seu trabalho histórico (Passivo de Gratidão).',
                'image_url' => '🤝',
                'gradiente' => 'from-emerald-500/20 to-teal-500/0'
            ],
            [
                'title' => 'Nossas Cadências Semanais',
                'description' => 'A transparência é o que nos move. É obrigatório manter o seu status.md semanal e o seu metas.md mensal atualizados dentro da sua pasta na Wiki.',
                'image_url' => '🔄',
                'gradiente' => 'from-pink-500/20 to-rose-500/0'
            ],
            [
                'title' => 'Linha de Comando Própria',
                'description' => 'Use o crom.sh para navegar na Wiki local e falar com a Rosa (nossa IA). No servidor, utilize a CLI crom-ws para gerenciar containers e publicar aplicações web com HTTPS em segundos.',
                'image_url' => '💻',
                'gradiente' => 'from-cyan-500/20 to-sky-500/0'
            ]
        ];

        $this->insert('welcome_sliders', [
            'title' => 'Bem-vindo',
            'badge_text' => 'Novo',
            'icon' => '👋',
            'required_role' => 'all', // Permite que todos vejam por padrão para testes
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
