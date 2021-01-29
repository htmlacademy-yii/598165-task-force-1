<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%event}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m210128_093300_add_addressee_column_to_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%event}}', 'addressee', $this->integer());

        // creates index for column `addressee`
        $this->createIndex(
            '{{%idx-event-addressee}}',
            '{{%event}}',
            'addressee'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-event-addressee}}',
            '{{%event}}',
            'addressee',
            '{{%user}}',
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
            '{{%fk-event-addressee}}',
            '{{%event}}'
        );

        // drops index for column `addressee`
        $this->dropIndex(
            '{{%idx-event-addressee}}',
            '{{%event}}'
        );

        $this->dropColumn('{{%event}}', 'addressee');
    }
}
