<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $src
 * @property string $name
 *
 * @property TaskFile[] $taskFiles
 * @property Task[] $tasks
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['src', 'name'], 'required'],
            [['src', 'name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'src' => 'Src',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[TaskFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFiles()
    {
        return $this->hasMany(TaskFile::className(), ['file_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['id' => 'task_id'])->viaTable('task_file', ['file_id' => 'id']);
    }


    /**
     * Returns a file name with the path.
     * @return string
     */
    public function getPath(): string
    {
        return '/' . $this->src . '/' . $this->name;
    }
}
