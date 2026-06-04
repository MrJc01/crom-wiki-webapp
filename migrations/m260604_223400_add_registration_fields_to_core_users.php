<?php

use yii\db\Migration;

/**
 * Class m260604_223400_add_registration_fields_to_core_users
 */
class m260604_223400_add_registration_fields_to_core_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('core_users', 'email', $this->string()->null());
        $this->addColumn('core_users', 'whatsapp', $this->string()->null());
        $this->addColumn('core_users', 'discord', $this->string()->null());
        $this->addColumn('core_users', 'github', $this->string()->null());
        $this->addColumn('core_users', 'registration_ip', $this->string(45)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('core_users', 'email');
        $this->dropColumn('core_users', 'whatsapp');
        $this->dropColumn('core_users', 'discord');
        $this->dropColumn('core_users', 'github');
        $this->dropColumn('core_users', 'registration_ip');
    }
}
