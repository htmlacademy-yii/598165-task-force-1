<?php

use yii\db\Migration;

/**
 * Class m210113_092555_add_auto_increment_to_favorites
 */
class m210113_092555_add_auto_increment_to_favorites extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{favorite}}', 'id', $this->integer().' NOT NULL AUTO_INCREMENT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210113_092555_add_auto_increment_to_favorites cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210113_092555_add_auto_increment_to_favorites cannot be reverted.\n";

        return false;
    }
    */
}
