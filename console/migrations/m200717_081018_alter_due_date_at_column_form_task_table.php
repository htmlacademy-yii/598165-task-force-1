<?php

use yii\db\Migration;

/**
 * Class m200717_081018_alter_due_date_at_column_form_task_table
 */
class m200717_081018_alter_due_date_at_column_form_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('task', 'due_date_at', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200717_081018_alter_due_date_at_column_form_task_table cannot be reverted.\n";
        $this->alterColumn('task', 'due_date_at', $this->dateTime()->notNull());


        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200717_081018_alter_due_date_at_column_form_task_table cannot be reverted.\n";

        return false;
    }
    */
}
