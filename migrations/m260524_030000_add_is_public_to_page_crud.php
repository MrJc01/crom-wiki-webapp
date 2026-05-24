<?php

use yii\db\Migration;

/**
 * Class m260524_030000_add_is_public_to_page_crud
 */
class m260524_030000_add_is_public_to_page_crud extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('page_crud_pages', 'is_public', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('page_crud_pages', 'is_public');
    }
}
