<?php

use yii\db\Migration;

/**
 * Class m260524_215000_add_system_chat_column
 */
class m260524_215000_add_system_chat_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('chat_rooms', 'is_system', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('chat_rooms', 'is_system');
    }
}
