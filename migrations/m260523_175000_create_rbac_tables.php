<?php

use yii\db\Migration;

class m260523_175000_create_rbac_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Tabela auth_rule
        $this->createTable('auth_rule', [
            'name' => $this->string(64)->notNull(),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
        ]);

        // 2. Tabela auth_item
        $this->createTable('auth_item', [
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
            'FOREIGN KEY (rule_name) REFERENCES auth_rule (name) ON DELETE SET NULL ON UPDATE CASCADE',
        ]);

        // Índices secundários do auth_item
        $this->createIndex('idx-auth_item-type', 'auth_item', 'type');

        // 3. Tabela auth_item_child
        $this->createTable('auth_item_child', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES auth_item (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (child) REFERENCES auth_item (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ]);

        // 4. Tabela auth_assignment
        $this->createTable('auth_assignment', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES auth_item (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ]);

        // 5. Adicionar a coluna required_permission na tabela core_modules
        $this->addColumn('core_modules', 'required_permission', $this->string(64)->null());

        // 6. Criar e popular a permissão inicial para a Wiki
        $time = time();
        $this->insert('auth_item', [
            'name' => 'access-wiki',
            'type' => 2, // 2 = Permission no Yii2 RBAC
            'description' => 'Acesso completo ao módulo Wiki Interna',
            'created_at' => $time,
            'updated_at' => $time,
        ]);

        // Associar a permissão ao usuário administrador padrão (user_id '1')
        $this->insert('auth_assignment', [
            'item_name' => 'access-wiki',
            'user_id' => '1',
            'created_at' => $time,
        ]);

        // Atualizar o módulo wiki para exigir esta permissão
        $this->update('core_modules', [
            'required_permission' => 'access-wiki'
        ], ['id' => 'wiki']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Limpar coluna required_permission
        $this->update('core_modules', ['required_permission' => null]);
        $this->dropColumn('core_modules', 'required_permission');

        // Dropar tabelas do RBAC
        $this->dropTable('auth_assignment');
        $this->dropTable('auth_item_child');
        $this->dropIndex('idx-auth_item-type', 'auth_item');
        $this->dropTable('auth_item');
        $this->dropTable('auth_rule');
    }
}
