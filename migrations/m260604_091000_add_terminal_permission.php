<?php

use yii\db\Migration;

/**
 * Class m260604_091000_add_terminal_permission
 */
class m260604_091000_add_terminal_permission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $time = time();
        
        // 1. Criar a permissão 'access-terminal' se não existir
        $exists = (new \yii\db\Query())
            ->from('auth_item')
            ->where(['name' => 'access-terminal'])
            ->exists();
            
        if (!$exists) {
            $this->insert('auth_item', [
                'name' => 'access-terminal',
                'type' => 2, // Permission
                'description' => 'Acesso ao terminal SSH via web',
                'created_at' => $time,
                'updated_at' => $time,
            ]);
        }

        // 2. Associar ao admin (user_id '1') se não estiver associado
        $assigned = (new \yii\db\Query())
            ->from('auth_assignment')
            ->where(['item_name' => 'access-terminal', 'user_id' => '1'])
            ->exists();
            
        if (!$assigned) {
            $this->insert('auth_assignment', [
                'item_name' => 'access-terminal',
                'user_id' => '1',
                'created_at' => $time,
            ]);
        }

        // 3. Atualizar o módulo terminal para exigir esta permissão
        $this->update('core_modules', [
            'required_permission' => 'access-terminal'
        ], ['id' => 'terminal']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // 1. Limpar a permissão requerida do módulo terminal
        $this->update('core_modules', [
            'required_permission' => null
        ], ['id' => 'terminal']);

        // 2. Remover associações
        $this->delete('auth_assignment', ['item_name' => 'access-terminal']);

        // 3. Remover a permissão
        $this->delete('auth_item', ['name' => 'access-terminal']);
    }
}
