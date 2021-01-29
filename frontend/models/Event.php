<?php

namespace frontend\models;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string|null $type
 * @property int|null $task_id
 * @property string|null $status
 * @property int|null $addressee
 *
 * @property User $addressee0
 * @property Task $task
 */
class Event extends ActiveRecord
{
    const START_TASK = 'START_TASK';
    const FINISH_TASK = 'FINISH_TASK';
    const REJECT_TASK = 'REJECT_TASK';
    const NEW_RESPONSE = 'NEW_RESPONSE';
    const NEW_MESSAGE = 'NEW_MESSAGE';

    const STATUS_NEW = 'NEW';
    const STATUS_READ = 'READ';

    const TYPE = [
        self::START_TASK => 'Выбран исполнитель для',
        self::FINISH_TASK => 'Завершено задание',
        self::REJECT_TASK => 'Отказ от',
        self::NEW_RESPONSE => 'Новый отклик на',
        self::NEW_MESSAGE => 'Новое сообщение в чате',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status'], 'string'],
            [['task_id', 'addressee'], 'integer'],
            [['addressee'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['addressee' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'task_id' => 'Task ID',
            'status' => 'Status',
            'addressee' => 'Addressee',
        ];
    }

    /**
     * Gets query for [[Addressee0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddressee0()
    {
        return $this->hasOne(User::className(), ['id' => 'addressee']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /** Send notification email
     * @param string $message
     */
    public function notify(string $message)
    {
        $addressee = User::findOne($this->addressee);
        $mailBody = sprintf('%s <a href="http://taskforce.loc/tasks/view/%s"> %s</a>', $message, $this->task->id, $this->task->title);

        \Yii::$app->mailer->compose()
            ->setFrom('noreply@taskforce.loc')
            ->setTo($addressee->email)
            ->setSubject($message)
            ->setHtmlBody($mailBody)
            ->send();
    }

    public function beforeSave($insert) : bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->addressee = Task::findOne($this->task_id)->findAddresseeForTaskEvent();
        return true;
    }

}
