<?php

use yii\db\Migration;

/**
 * Class m260524_180000_add_terminal_module
 */
class m260524_180000_add_terminal_module extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('core_modules', [
            'id' => 'terminal',
            'name' => 'CROM Terminal',
            'entry_point' => 'terminal/default/index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" /></svg>',
            'sort_order' => 6,
            'is_active' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('core_modules', ['id' => 'terminal']);
    }
}
