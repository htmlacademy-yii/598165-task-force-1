<?php

use yii\db\Migration;

/**
 * Class m200602_063214_add_default_user_profile_read
 */
class m200602_063214_add_default_user_profile_read extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%user}}', 'profile_read', $this->integer()->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%user}}', 'profile_read', $this->integer());

    }

}
