<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%response}}`.
 */
class m200731_081112_add_status_column_to_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'response',
            'status',
            "ENUM('PENDING', 'DECLINED', 'ACCEPTED') DEFAULT 'PENDING'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('response', 'status');
    }
}
