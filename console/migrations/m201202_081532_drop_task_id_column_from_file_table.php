<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%file}}`.
 */
class m201202_081532_drop_task_id_column_from_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('file_ibfk_1', '{{%file}}');
        $this->dropColumn('{{%file}}', 'task_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%file}}', 'task_id', $this->integer());
        $this->addForeignKey('file_ibfk_1', '{{%file}}', 'task_id', '{{%task}}', 'id', 'CASCADE');

    }
}
