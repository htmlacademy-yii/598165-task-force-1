<?php

namespace frontend\models;


use Yii;
use yii\db\ActiveQuery;
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
    public static function tableName(): string
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type', 'status'], 'string'],
            [['task_id', 'addressee'], 'integer'],
            [
                ['addressee'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['addressee' => 'id']
            ],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Task::className(),
                'targetAttribute' => ['task_id' => 'id']
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
            'type' => 'Type',
            'task_id' => 'Task ID',
            'status' => 'Status',
            'addressee' => 'Addressee',
        ];
    }

    /**
     * Gets query for [[Addressee0]].
     *
     * @return ActiveQuery
     */
    public function getAddressee0(): ActiveQuery
    {
        return $this->hasOne(User::className(), ['id' => 'addressee']);
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

    /** Send notification email
     * @param string $message
     * @return bool
     */
    public function notify(string $message): bool
    {
        if (!$this->checkAddresseePreferences()) {
            return false;
        }

        $addressee = User::findOne($this->addressee);
        $mailBody = sprintf('%s <a href="http://taskforce.loc/tasks/view/%s"> %s</a>', $message, $this->task->id,
            $this->task->title);

        return Yii::$app->mailer->compose()
            ->setFrom('noreply@taskforce.loc')
            ->setTo($addressee->email)
            ->setSubject($message)
            ->setHtmlBody($mailBody)
            ->send();
    }

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->addressee = Task::findOne($this->task_id)->findAddresseeForTaskEvent();
        return true;
    }

    /** Checks the addressee's preferences to receive notifications
     * @return bool
     */
    private function checkAddresseePreferences() : bool
    {
        $addressee = User::findOne($this->addressee);

        switch ($this->type) {
            case self::NEW_MESSAGE :
                if (!$addressee->is_notify_message) {
                    return false;
                }
                break;
            case self::START_TASK :
            case self::FINISH_TASK :
            case self::REJECT_TASK :
                if (!$addressee->is_notify_action) {
                    return false;
                }
                break;
            case self::NEW_RESPONSE :
                if (!$addressee->is_notify_review) {
                    return false;
                }
                break;
        }
        return true;
    }

}
