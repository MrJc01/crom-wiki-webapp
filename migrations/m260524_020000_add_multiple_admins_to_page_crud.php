<?php

use yii\db\Migration;

/**
 * Class m260524_020000_add_multiple_admins_to_page_crud
 */
class m260524_020000_add_multiple_admins_to_page_crud extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Limpeza preventiva para re-execução segura caso haja resquícios de falha anterior
        try {
            $this->dropTable('page_crud_page_admins');
        } catch (\Exception $e) {
            // Silencia
        }

        // 1. Criar a tabela associativa N:N com Primary Key e Foreign Keys declaradas inline (padrão SQLite nativo)
        $this->createTable('page_crud_page_admins', [
            'page_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'PRIMARY KEY (page_id, user_id)',
            'FOREIGN KEY (page_id) REFERENCES page_crud_pages(id) ON DELETE CASCADE',
            'FOREIGN KEY (user_id) REFERENCES core_users(id) ON DELETE CASCADE',
        ]);

        // 2. Migrar os dados existentes de admin_id na tabela page_crud_pages para a nova tabela associativa
        try {
            $pages = (new \yii\db\Query())
                ->select(['id', 'admin_id'])
                ->from('page_crud_pages')
                ->where('admin_id IS NOT NULL')
                ->all();

            foreach ($pages as $p) {
                $userExists = (new \yii\db\Query())
                    ->from('core_users')
                    ->where(['id' => $p['admin_id']])
                    ->exists();
                    
                if ($userExists) {
                    $this->insert('page_crud_page_admins', [
                        'page_id' => $p['id'],
                        'user_id' => $p['admin_id']
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silencia caso ocorra algum problema na tabela de páginas não inicializada
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('page_crud_page_admins');
    }
}
