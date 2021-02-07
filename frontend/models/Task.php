<?php

namespace frontend\models;


use TaskForce\actions\CancelAction;
use TaskForce\actions\FinishAction;
use TaskForce\actions\NoAction;
use TaskForce\actions\RejectAction;
use TaskForce\actions\StartingAction;
use TaskForce\models\PersonalTasks;
use TaskForce\models\TaskStatus;
use TaskForce\models\UserRole;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property int|null $city_id
 * @property string|null $address
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int|null $budget
 * @property string $created_at
 * @property string $updated_at
 * @property string $due_date_at
 * @property int $client_id
 * @property int|null $contractor_id
 * @property int $skill_id
 *
 * @property Message[] $messages
 * @property Response[] $responses
 * @property Review[] $reviews
 * @property Skill $skill
 * @property City $city
 * @property User $client
 * @property User $contractor
 * @property TaskHasFiles[] $taskFiles
 * @property File[] $files
 */
class Task extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'status',  'client_id', 'skill_id'], 'required'],
            [['title', 'description', 'status', 'address'], 'string'],
            [['city_id', 'budget', 'client_id', 'contractor_id', 'skill_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['created_at', 'updated_at', 'due_date_at'], 'safe'],
            [['skill_id'], 'exist', 'skipOnError' => true, 'targetClass' => Skill::className(), 'targetAttribute' => ['skill_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['contractor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['contractor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'city_id' => 'City ID',
            'address' => 'Address',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'budget' => 'Budget',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'due_date_at' => 'Due Date At',
            'client_id' => 'Client ID',
            'contractor_id' => 'Contractor ID',
            'skill_id' => 'Skill ID',
        ];
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Skill]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkill()
    {
        return $this->hasOne(Skill::className(), ['id' => 'skill_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(User::className(), ['id' => 'client_id']);
    }

    /**
     * Gets query for [[Contractor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContractor()
    {
        return $this->hasOne(User::className(), ['id' => 'contractor_id']);
    }

    /**
     * Gets query for [[TaskFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFiles()
    {
        return $this->hasMany(TaskHasFiles::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['id' => 'file_id'])->viaTable('task_file', ['task_id' => 'id']);
    }

    /**
     * Gets available actions
     *
     * @return array
     */
    public function actions(): array
    {
        $actions = [
            TaskStatus::NEW => [
                UserRole::CLIENT => new CancelAction(),
                UserRole::CONTRACTOR => new StartingAction()
            ],
            TaskStatus::CANCELED => [
                UserRole::CLIENT  => new NoAction(),
                UserRole::CONTRACTOR => new NoAction()
            ],
            TaskStatus::PENDING => [
                UserRole::CLIENT  => new FinishAction(),
                UserRole::CONTRACTOR => new RejectAction()
            ],
            TaskStatus::DONE => [
                UserRole::CLIENT  => new NoAction(),
                UserRole::CONTRACTOR => new NoAction()
            ],
            TaskStatus::FAILED => [
                UserRole::CLIENT  => new NoAction(),
                UserRole::CONTRACTOR => new NoAction()
            ]
        ];
        return $actions[$this->status];
    }

    /**
     * Get personal tasks.
     *
     * @param string $filter
     * @return  ActiveQuery
     */
    public static function getPersonalTasks(string $filter): ActiveQuery {
        $tasks = Task::find()
            ->where(['contractor_id' => \Yii::$app->user->id])
            ->orWhere(['client_id' => \Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->with(['skill', 'client', 'contractor']);

        switch ($filter) {
            case PersonalTasks::FILTER_COMPLETED :
                return $tasks->andWhere(['status' => TaskStatus::DONE]);
            case  PersonalTasks::FILTER_NEW :
                return $tasks->andWhere(['status' => TaskStatus::NEW]);
            case PersonalTasks::FILTER_PENDING :
                return $tasks->andWhere(['status' => TaskStatus::PENDING]);
            case PersonalTasks::FILTER_CANCELED :
                return $tasks
                    ->andWhere(['status' => TaskStatus::CANCELED])
                    ->orWhere(['status' => TaskStatus::FAILED]);
            case PersonalTasks::FILTER_EXPIRED :
                return $tasks
                    ->andWhere(['is not', 'due_date_at', null])
                    ->andWhere(['<', 'due_date_at', date('Y-m-d H:i:s')]);
        }

        return $tasks;
    }

    /**
     * Find an addressee for the notification about the task event
     * @return int|null
     */
    public function findAddresseeForTaskEvent() : ?int
    {
        $user_id = \Yii::$app->user->id;
        if ($this->client_id === $user_id) {
            return $this->contractor_id;
        }
        if ($this->contractor_id === $user_id) {
            return $this->client_id;
        }
        if ($this->status === TaskStatus::NEW) {
            return $this->client_id;
        }
        return null;
    }

}
