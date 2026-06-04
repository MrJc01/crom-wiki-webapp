<?php

use yii\db\Migration;

class m260604_201000_create_iframe_modules extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Adicionar colunas category e description na tabela core_modules
        $this->addColumn('core_modules', 'category', $this->string(64)->null());
        $this->addColumn('core_modules', 'description', $this->text()->null());

        // 2. Atualizar registros dos módulos existentes
        $this->update('core_modules', [
            'category' => 'Produtividade',
            'description' => 'Wiki colaborativa GitOps descentralizada. Crie e organize páginas Markdown com atribuição de criadores e administradores de forma integrada.'
        ], ['id' => 'wiki']);

        $this->update('core_modules', [
            'category' => 'Produtividade',
            'description' => 'Gerenciador de páginas dinâmicas para criar, ler, editar e remover documentações de forma rápida.'
        ], ['id' => 'page_crud']);

        $this->update('core_modules', [
            'category' => 'Comunicação',
            'description' => 'Sistema de chat persistente e bate-papo de membros em canais organizados.'
        ], ['id' => 'chat']);

        $this->update('core_modules', [
            'category' => 'Segurança',
            'description' => 'Painel Administrativo completo para configuração global, gerenciamento de permissões e controle de usuários.'
        ], ['id' => 'admin']);

        $this->update('core_modules', [
            'category' => 'Desenvolvedor',
            'description' => 'Crie e gerencie endpoints JSON dinâmicos públicos ou privados de forma rápida.'
        ], ['id' => 'json_store']);

        $this->update('core_modules', [
            'category' => 'Desenvolvedor',
            'description' => 'Terminal web sandboxed seguro para SRE e administração remota.'
        ], ['id' => 'terminal']);

        $this->update('core_modules', [
            'category' => 'Comunicação',
            'description' => 'Suporte interno e governança para a resolução de demandas dos membros.'
        ], ['id' => 'tickets']);

        $this->update('core_modules', [
            'category' => 'Produtividade',
            'description' => 'Acesso unificado via chaves privadas a modelos de IA avançados sem vazamento de dados.'
        ], ['id' => 'cromia']);

        // 3. Cadastrar os novos 4 módulos de iframe
        // ferramentas
        $this->insert('core_modules', [
            'id' => 'ferramentas',
            'name' => 'Ferramentas',
            'entry_point' => 'ferramentas/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.83-5.83m0 0a2.999 2.999 0 00-4.14-4.14L4.855 1.03a1.5 1.5 0 10-2.122 2.121L8.91 9.33a2.999 2.999 0 004.14 4.14zM15.5 16.5L14 15" /></svg>',
            'sort_order' => 11,
            'is_active' => true,
            'required_permission' => null,
            'category' => 'Produtividade',
            'description' => 'Coleção de ferramentas web essenciais, gratuitas e 100% privadas. Inclui conversores, geradores e utilitários para desenvolvedores e criadores.'
        ]);

        // p2p
        $this->insert('core_modules', [
            'id' => 'p2p',
            'name' => 'P2P Secure Share',
            'entry_point' => 'p2p/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>',
            'sort_order' => 12,
            'is_active' => true,
            'required_permission' => null,
            'category' => 'Produtividade',
            'description' => 'Transferência direta de arquivos de dispositivo para dispositivo via WebRTC, rodando sem backend centralizado.'
        ]);

        // comva
        $this->insert('core_modules', [
            'id' => 'comva',
            'name' => 'Cromva',
            'entry_point' => 'comva/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>',
            'sort_order' => 13,
            'is_active' => true,
            'required_permission' => null,
            'category' => 'Produtividade',
            'description' => 'Focado na privacidade, o Cromva é uma plataforma voltada para o consumo e organização de conteúdo em markdown.'
        ]);

        // dokploy
        $this->insert('core_modules', [
            'id' => 'dokploy',
            'name' => 'Dokploy',
            'entry_point' => 'dokploy/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18m-18 0v-2.25C2.25 9.373 3.373 8.25 4.75 8.25h14.5c1.377 0 2.5 1.123 2.5 2.5v2.25m-18 0V17.25c0 1.377 1.123 2.5 2.5 2.5h14.5c1.377 0 2.5-1.123 2.5-2.5V13.5M9 7.5l3-3 3 3M12 4.5v10.5" /></svg>',
            'sort_order' => 14,
            'is_active' => true,
            'required_permission' => 'admin-access',
            'category' => 'Desenvolvedor',
            'description' => 'Painel administrativo central de deploy e controle de microsserviços. Gerenciamento de containers Docker.'
        ]);

        // 4. Atualizar a configuração global ecosystem_cards_json no banco
        try {
            $currentSetting = (new \yii\db\Query())
                ->select(['value'])
                ->from('core_settings')
                ->where(['key' => 'ecosystem_cards_json'])
                ->scalar();

            if ($currentSetting) {
                $cards = json_decode($currentSetting, true);
                if (is_array($cards)) {
                    $updated = false;
                    foreach ($cards as &$card) {
                        if ($card['nome'] === 'Ferramentas') {
                            unset($card['link']);
                            $card['tab'] = 'ferramentas';
                            $updated = true;
                        } elseif ($card['nome'] === 'P2P Secure Share') {
                            unset($card['link']);
                            $card['tab'] = 'p2p';
                            $card['btn_texto'] = 'Abrir P2PFile';
                            $updated = true;
                        } elseif ($card['nome'] === 'Cromva') {
                            unset($card['link']);
                            $card['tab'] = 'comva';
                            $updated = true;
                        } elseif ($card['nome'] === 'DokployCrom' || $card['nome'] === 'Dokploy') {
                            $card['nome'] = 'Dokploy';
                            unset($card['link']);
                            $card['tab'] = 'dokploy';
                            $card['btn_texto'] = 'Abrir Dokploy';
                            $card['required_permission'] = 'admin-access';
                            $updated = true;
                        }
                    }

                    // Se não tiver Dokploy, adiciona
                    $hasDokploy = false;
                    foreach ($cards as $card) {
                        if ($card['nome'] === 'Dokploy') {
                            $hasDokploy = true;
                            break;
                        }
                    }
                    if (!$hasDokploy) {
                        $cards[] = [
                            'nome' => 'Dokploy',
                            'tag' => 'Infraestrutura',
                            'tag_style' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                            'bg_style' => 'bg-gradient-to-br from-indigo-950/40 to-slate-900 border-indigo-900/60 hover:border-indigo-500/40',
                            'icone' => '🚀',
                            'descricao' => 'Painel administrativo central de deploy e controle de microsserviços. Gerenciamento de containers Docker.',
                            'btn_texto' => 'Abrir Dokploy',
                            'btn_style' => 'bg-indigo-600 hover:bg-indigo-500 text-white',
                            'disabled' => false,
                            'tab' => 'dokploy'
                        ];
                        $updated = true;
                    }

                    if ($updated) {
                        $this->update('core_settings', [
                            'value' => json_encode($cards, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                        ], ['key' => 'ecosystem_cards_json']);
                    }
                }
            }
        } catch (\Exception $e) {
            // Silencia caso ocorra erro no parse do JSON
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('core_modules', ['id' => ['ferramentas', 'p2p', 'comva', 'dokploy']]);
        $this->dropColumn('core_modules', 'description');
        $this->dropColumn('core_modules', 'category');
    }
}
