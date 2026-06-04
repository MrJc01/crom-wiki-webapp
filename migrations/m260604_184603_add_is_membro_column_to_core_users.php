<?php

use yii\db\Migration;

class m260604_184603_add_is_membro_column_to_core_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('core_users', 'is_membro', $this->boolean()->defaultValue(true));
        $this->update('core_users', ['is_membro' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('core_users', 'is_membro');
    }
}
