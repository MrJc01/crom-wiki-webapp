<?php

use yii\db\Migration;

/**
 * Class m260524_034200_create_chat_tables
 */
class m260524_034200_create_chat_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Tabela chat_rooms (Salas e Grupos)
        $this->createTable('chat_rooms', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'is_group' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'invite_code' => $this->string(64)->unique(),
        ]);

        // 2. Tabela chat_room_members (Associação de membros)
        $this->createTable('chat_room_members', [
            'room_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'joined_at' => $this->integer()->notNull(),
            'PRIMARY KEY (room_id, user_id)',
        ]);

        // 3. Tabela chat_messages (Histórico de mensagens e logs do sistema)
        $this->createTable('chat_messages', [
            'id' => $this->primaryKey(),
            'room_id' => $this->integer()->notNull(),
            'sender_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'is_system' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
        ]);

        // Inserir o registro de módulo na tabela core_modules
        $this->insert('core_modules', [
            'id' => 'chat',
            'name' => 'Conversas & Grupos',
            'entry_point' => 'chat/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a.75.75 0 01-1.074-.765 6 6 0 001.942-3.483C5.1 15.358 4 13.784 4 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>',
            'sort_order' => 5,
            'is_active' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('core_modules', ['id' => 'chat']);
        $this->dropTable('chat_messages');
        $this->dropTable('chat_room_members');
        $this->dropTable('chat_rooms');
    }
}
