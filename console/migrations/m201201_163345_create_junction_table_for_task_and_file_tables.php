<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_file}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%task}}`
 * - `{{%file}}`
 */
class m201201_163345_create_junction_table_for_task_and_file_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_file}}', [
            'task_id' => $this->integer(),
            'file_id' => $this->integer(),
            'PRIMARY KEY(task_id, file_id)',
        ]);

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-task_file-task_id}}',
            '{{%task_file}}',
            'task_id'
        );

        // add foreign key for table `{{%task}}`
        $this->addForeignKey(
            '{{%fk-task_file-task_id}}',
            '{{%task_file}}',
            'task_id',
            '{{%task}}',
            'id',
            'CASCADE'
        );

        // creates index for column `file_id`
        $this->createIndex(
            '{{%idx-task_file-file_id}}',
            '{{%task_file}}',
            'file_id'
        );

        // add foreign key for table `{{%file}}`
        $this->addForeignKey(
            '{{%fk-task_file-file_id}}',
            '{{%task_file}}',
            'file_id',
            '{{%file}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%task}}`
        $this->dropForeignKey(
            '{{%fk-task_file-task_id}}',
            '{{%task_file}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-task_file-task_id}}',
            '{{%task_file}}'
        );

        // drops foreign key for table `{{%file}}`
        $this->dropForeignKey(
            '{{%fk-task_file-file_id}}',
            '{{%task_file}}'
        );

        // drops index for column `file_id`
        $this->dropIndex(
            '{{%idx-task_file-file_id}}',
            '{{%task_file}}'
        );

        $this->dropTable('{{%task_file}}');
    }
}
