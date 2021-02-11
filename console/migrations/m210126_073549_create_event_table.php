<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event}}`.
 */
class m210126_073549_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event}}', [
            'id' => $this->primaryKey(),
            'type' => "ENUM('START_TASK', 'FINISH_TASK', 'REJECT_TASK', 'NEW_MESSAGE', 'NEW_RESPONSE')",
            'task_id' => $this->integer(),
            'status' => "ENUM('NEW', 'READ') DEFAULT 'NEW'"
        ]);

        $this->addForeignKey('fk-event-task_id',
            'event',
            'task_id',
            'task',
            'id',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-event-task_id', 'event');
        $this->dropTable('{{%event}}');
    }
}
