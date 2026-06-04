<?php

use yii\db\Migration;

class m260604_222113_create_mblog_mapp_modules extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Inserir módulo mblog
        $this->insert('core_modules', [
            'id' => 'mblog',
            'name' => 'Log_Diário',
            'entry_point' => 'mblog/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 17.75V5.25A2.25 2.25 0 015.25 3h6a2.25 2.25 0 012.25 2.25v2.25z" /></svg>',
            'sort_order' => 15,
            'is_active' => true,
            'required_permission' => null,
            'category' => 'MiniBlog',
            'description' => 'Feed direto da comunidade. Reflexões rápidas, atualizações de desenvolvimento e logs não-filtrados sobre tecnologia e soberania.'
        ]);

        // 2. Inserir módulo mapp
        $this->insert('core_modules', [
            'id' => 'mapp',
            'name' => 'Arsenal_Tático',
            'entry_point' => 'mapp/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>',
            'sort_order' => 16,
            'is_active' => true,
            'required_permission' => null,
            'category' => 'MiniApps',
            'description' => 'Micro-aplicações que usam os pontos dos membros do miniblog para criação de conteúdos organizados e dinâmicos.'
        ]);

        // 3. Atualizar a configuração global ecosystem_cards_json no banco
        try {
            $currentSetting = (new \yii\db\Query())
                ->select(['value'])
                ->from('core_settings')
                ->where(['key' => 'ecosystem_cards_json'])
                ->scalar();

            if ($currentSetting) {
                $cards = json_decode($currentSetting, true);
                if (is_array($cards)) {
                    $hasMblog = false;
                    $hasMapp = false;
                    foreach ($cards as $card) {
                        if (isset($card['nome']) && $card['nome'] === 'Log_Diário') {
                            $hasMblog = true;
                        }
                        if (isset($card['nome']) && $card['nome'] === 'Arsenal_Tático') {
                            $hasMapp = true;
                        }
                    }

                    $updated = false;
                    if (!$hasMblog) {
                        $cards[] = [
                            'nome' => 'Log_Diário',
                            'tag' => 'MiniBlog',
                            'tag_style' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                            'bg_style' => 'bg-gradient-to-br from-rose-950/40 to-slate-900 border-rose-900/60 hover:border-rose-500/40',
                            'icone' => '📝',
                            'descricao' => 'Feed direto da comunidade. Reflexões rápidas, atualizações de desenvolvimento e logs não-filtrados sobre tecnologia e soberania.',
                            'btn_texto' => 'Acessar Feed',
                            'btn_style' => 'bg-rose-600 hover:bg-rose-500 text-white',
                            'disabled' => false,
                            'tab' => 'mblog'
                        ];
                        $updated = true;
                    }

                    if (!$hasMapp) {
                        $cards[] = [
                            'nome' => 'Arsenal_Tático',
                            'tag' => 'MiniApps',
                            'tag_style' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                            'bg_style' => 'bg-gradient-to-br from-emerald-950/40 to-slate-900 border-emerald-900/60 hover:border-emerald-500/40',
                            'icone' => '⚡',
                            'descricao' => 'Micro-aplicações que usam os pontos dos membros do miniblog para criação de conteúdos organizados e dinâmicos.',
                            'btn_texto' => 'Explorar Apps',
                            'btn_style' => 'bg-emerald-600 hover:bg-emerald-500 text-white',
                            'disabled' => false,
                            'tab' => 'mapp'
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
        $this->delete('core_modules', ['id' => ['mblog', 'mapp']]);

        try {
            $currentSetting = (new \yii\db\Query())
                ->select(['value'])
                ->from('core_settings')
                ->where(['key' => 'ecosystem_cards_json'])
                ->scalar();

            if ($currentSetting) {
                $cards = json_decode($currentSetting, true);
                if (is_array($cards)) {
                    $newCards = [];
                    foreach ($cards as $card) {
                        if (isset($card['nome']) && ($card['nome'] === 'Log_Diário' || $card['nome'] === 'Arsenal_Tático')) {
                            continue;
                        }
                        $newCards[] = $card;
                    }
                    $this->update('core_settings', [
                        'value' => json_encode($newCards, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                    ], ['key' => 'ecosystem_cards_json']);
                }
            }
        } catch (\Exception $e) {
            // Silencia
        }
    }
}

