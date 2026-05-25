<?php

use yii\db\Migration;

/**
 * Class m260524_214000_create_tickets_tables
 */
class m260524_214000_create_tickets_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Adicionar tags na tabela core_users
        $this->addColumn('core_users', 'is_guardiao', $this->boolean()->defaultValue(false));
        $this->addColumn('core_users', 'is_pilar', $this->boolean()->defaultValue(false));
        $this->addColumn('core_users', 'is_forja', $this->boolean()->defaultValue(false));

        // 2. Criar tabela support_tickets
        $this->createTable('support_tickets', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'type' => $this->string(32)->notNull(), // 'idea', 'bug_fix', 'project', 'other'
            'status' => $this->string(32)->notNull()->defaultValue('open'), // 'open', 'assigned', 'closed'
            'created_by' => $this->integer()->notNull(),
            'assigned_to' => $this->integer()->null(),
            'req_guardiao' => $this->boolean()->defaultValue(false),
            'req_pilar' => $this->boolean()->defaultValue(false),
            'req_forja' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Criar chaves estrangeiras e índices para suporte
        $this->createIndex('idx-support_tickets-created_by', 'support_tickets', 'created_by');
        $this->createIndex('idx-support_tickets-assigned_to', 'support_tickets', 'assigned_to');

        // 3. Criar tabela support_ticket_messages (Histórico de chats do ticket)
        $this->createTable('support_ticket_messages', [
            'id' => $this->primaryKey(),
            'ticket_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-support_ticket_messages-ticket_id', 'support_ticket_messages', 'ticket_id');
        $this->createIndex('idx-support_ticket_messages-user_id', 'support_ticket_messages', 'user_id');

        // 4. Inserir o módulo na tabela core_modules
        $this->insert('core_modules', [
            'id' => 'tickets',
            'name' => 'Central de Tickets',
            'entry_point' => 'tickets/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-3-12h.008v.008H13.5V6zm0 3h.008v.008H13.5V9zm0 3h.008v.008H13.5v-.008zm0 3h.008v.008H13.5v-.008zm-9-6h5.25a3 3 0 013 3v3a3 3 0 01-3 3H4.5a3 3 0 01-3-3v-3a3 3 0 013-3zm0 9h15.75c.621 0 1.125-.504 1.125-1.125V6.375C22.5 5.754 21.996 5.25 21.375 5.25H4.5A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" /></svg>',
            'sort_order' => 6,
            'is_active' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // 1. Deletar registro de módulo
        $this->delete('core_modules', ['id' => 'tickets']);

        // 2. Excluir tabelas
        $this->dropTable('support_ticket_messages');
        $this->dropTable('support_tickets');

        // 3. Remover colunas da tabela core_users
        $this->dropColumn('core_users', 'is_guardiao');
        $this->dropColumn('core_users', 'is_pilar');
        $this->dropColumn('core_users', 'is_forja');
    }
}
