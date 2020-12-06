<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_file}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%file}}`
 */
class m201205_100323_create_junction_table_for_user_and_file_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_file}}', [
            'user_id' => $this->integer(),
            'file_id' => $this->integer(),
            'PRIMARY KEY(user_id, file_id)',
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_file-user_id}}',
            '{{%user_file}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_file-user_id}}',
            '{{%user_file}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `file_id`
        $this->createIndex(
            '{{%idx-user_file-file_id}}',
            '{{%user_file}}',
            'file_id'
        );

        // add foreign key for table `{{%file}}`
        $this->addForeignKey(
            '{{%fk-user_file-file_id}}',
            '{{%user_file}}',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_file-user_id}}',
            '{{%user_file}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_file-user_id}}',
            '{{%user_file}}'
        );

        // drops foreign key for table `{{%file}}`
        $this->dropForeignKey(
            '{{%fk-user_file-file_id}}',
            '{{%user_file}}'
        );

        // drops index for column `file_id`
        $this->dropIndex(
            '{{%idx-user_file-file_id}}',
            '{{%user_file}}'
        );

        $this->dropTable('{{%user_file}}');
    }
}
