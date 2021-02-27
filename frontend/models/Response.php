<?php

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "response".
 *
 * @property int $id
 * @property int $user_id
 * @property int $task_id
 * @property string $description
 * @property string $status
 * @property int|null $rate
 * @property int|null $budget
 * @property string $created_at
 *
 * @property User $user
 * @property Task $task
 */
class Response extends ActiveRecord
{
    const PENDING = 'PENDING';
    const DECLINED = 'DECLINED';
    const ACCEPTED = 'ACCEPTED';

    const STATUS = [
        self::PENDING, self::DECLINED, self::ACCEPTED
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'response';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'task_id', 'description'], 'required'],
            [['user_id', 'task_id', 'rate', 'budget'], 'integer'],
            [['description'], 'string'],
            [['created_at'], 'safe'],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Task::className(),
                'targetAttribute' => ['task_id' => 'id']
            ],
            [
                ['status'],
                'in',
                'range' => array_keys(self::STATUS),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'task_id' => 'Task ID',
            'description' => 'Description',
            'rate' => 'Rate',
            'budget' => 'Budget',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
