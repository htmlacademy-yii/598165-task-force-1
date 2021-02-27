<?php

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task_file".
 *
 * @property int $task_id
 * @property int $file_id
 *
 * @property File $file
 * @property Task $task
 */
class TaskHasFiles extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'task_file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['task_id', 'file_id'], 'required'],
            [['task_id', 'file_id'], 'integer'],
            [['task_id', 'file_id'], 'unique', 'targetAttribute' => ['task_id', 'file_id']],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['file_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'task_id' => 'Task ID',
            'file_id' => 'File ID',
        ];
    }

    /**
     * Gets query for [[File]].
     *
     * @return ActiveQuery
     */
    public function getFile(): ActiveQuery
    {
        return $this->hasOne(File::className(), ['id' => 'file_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}
