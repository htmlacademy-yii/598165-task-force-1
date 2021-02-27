<?php

namespace frontend\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $src
 * @property string $name
 *
 * @property Task[] $tasks
 */
class File extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['src', 'name'], 'required'],
            [['src', 'name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'src' => 'Src',
            'name' => 'Name',
        ];
    }


    /**
     * Gets query for [[Tasks]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getTasks(): ActiveQuery
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
