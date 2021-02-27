<?php

namespace frontend\models;

use DateTime;
use Exception;
use TaskForce\models\TaskStatus;
use TaskForce\models\UserRole;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $role
 * @property string $email
 * @property string $name
 * @property string|null $avatar
 * @property int $city_id
 * @property string|null $address
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $about
 * @property string|null $birthday_at
 * @property string $password
 * @property string|null $phone
 * @property string|null $skypeid
 * @property string|null $messenger
 * @property string $created_at
 * @property string $last_seen_at
 * @property int $is_notify_message
 * @property int $is_notify_action
 * @property int $is_notify_review
 * @property int $is_show_only_owner
 * @property int $is_hidden
 *
 * @property Auth[] $auths
 * @property Favorite[] $favorites
 * @property Favorite[] $favorites0
 * @property Message[] $messages
 * @property Response[] $responses
 * @property Review[] $reviews
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property City $city
 * @property UserHasSkill[] $userHasSkills
 * @property Skill[] $skills
 * @property UserHasFiles[] $userHasFiles
 * @property File[] $files
 */
class User extends ActiveRecord implements IdentityInterface
{

    private ?float $rating = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['name', 'string'],
            ['name', 'required'],
            [['role', 'avatar', 'address', 'about', 'password'], 'string'],
            ['avatar', 'default', 'value' => null],
            [['email', 'city_id', 'password'], 'required'],

            [
                [
                    'city_id',
                    'is_notify_message',
                    'is_notify_action',
                    'is_notify_review',
                    'is_show_only_owner',
                    'is_hidden'
                ],
                'integer'
            ],
            [['latitude', 'longitude'], 'number'],
            [['birthday_at', 'created_at', 'last_seen_at'], 'safe'],
            [['email'], 'string', 'max' => 320],
            [['phone'], 'string', 'max' => 11],
            [['skypeid', 'messenger'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [
                ['city_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => City::class,
                'targetAttribute' => ['city_id' => 'id']
            ],
            [
                ['skills'],
                'exist',
                'allowArray' => true,
                'targetClass' => Skill::class,
                'targetAttribute' => 'id'
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
            'role' => 'Role',
            'email' => 'Email',
            'name' => 'Name',
            'avatar' => 'Avatar',
            'city_id' => 'City ID',
            'address' => 'Address',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'about' => 'About',
            'birthday_at' => 'Birthday At',
            'password' => 'Password',
            'password_repeat' => 'Повтор пароля',
            'phone' => 'Phone',
            'skypeid' => 'Skypeid',
            'messenger' => 'Messenger',
            'created_at' => 'Created At',
            'last_seen_at' => 'Last Seen At',
            'is_notify_message' => 'Is Notify Message',
            'is_notify_action' => 'Is Notify Action',
            'is_notify_review' => 'Is Notify Review',
            'is_show_only_owner' => 'Is Show Only Owner',
            'is_hidden' => 'Is Hidden',
        ];
    }

    /**
     * Gets query for [[Favorites]].
     *
     * @return ActiveQuery
     */
    public function getFavoriteUsers(): ActiveQuery
    {
        return $this->hasMany(Favorite::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Favorites0]].
     *
     * @return ActiveQuery
     */
    public function getUsersHaveAddedToFavorites(): ActiveQuery
    {
        return $this->hasMany(Favorite::className(), ['favorite_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return ActiveQuery
     */
    public function getMessages(): ActiveQuery
    {
        return $this->hasMany(Message::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return ActiveQuery
     */
    public function getReviews(): ActiveQuery
    {
        return $this->hasMany(Review::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return ActiveQuery
     */
    public function getClientTasks(): ActiveQuery
    {
        return $this->hasMany(Task::className(), ['client_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return ActiveQuery
     */
    public function getContractorTasks(): ActiveQuery
    {
        return $this->hasMany(Task::className(), ['contractor_id' => 'id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[UserHasSkills]].
     *
     * @return ActiveQuery
     */
    public function getUserHasSkills(): ActiveQuery
    {
        return $this->hasMany(UserHasSkill::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Skills]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getSkills(): ActiveQuery
    {
        return $this->hasMany(Skill::className(), ['id' => 'skill_id'])
            ->viaTable('user_has_skill', ['user_id' => 'id']);
    }

    /**
     * Sets [[Skills]].
     *
     * @param $value
     */
    public function setSkills($value)
    {
        $this->skills = $value;
    }

    /**
     * Gets query for [[UserHasFiles]].
     *
     * @return ActiveQuery
     */
    public function getUserHasFiles(): ActiveQuery
    {
        return $this->hasMany(UserHasFiles::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getFiles(): ActiveQuery
    {
        return $this->hasMany(File::className(), ['id' => 'file_id'])->viaTable('user_file', ['user_id' => 'id']);

    }

    /**
     * Gets query for [[Auths]].
     *
     * @return ActiveQuery
     */
    public function getAuths(): ActiveQuery
    {
        return $this->hasMany(Auth::className(), ['user_id' => 'id']);
    }

    /**
     * Calculates user rating.
     *
     * @return float user rating
     */
    public function getRating(): float
    {
        if ($this->rating === null) {
            $reviewsCount = count($this->reviews);
            if ($reviewsCount) {
                $totalRating = 0;
                foreach ($this->reviews as $review) {
                    $totalRating += $review->rating;
                }
                $this->rating = $totalRating / $reviewsCount;
            } else {
                $this->rating = 0;
            }
        }

        return $this->rating;

    }

    /**
     * Calculates user age.
     *
     * @return string user age
     * @throws Exception
     */
    public function getAge(): ?string
    {
        if (!isset($this->birthday_at)) {
            return null;
        }
        $birthday = new DateTime($this->birthday_at);
        $now = new DateTime();
        $age = $birthday->diff(($now))->y;

        $inflections = [' лет', ' год', ' года', ' года', ' года', ' лет', ' лет', ' лет', ' лет', ' лет'];

        return $age . $inflections[$age % 10];
    }

    /**
     * Validates password.
     *
     * @param string $password
     * @return bool true if password is valid
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey(): string
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey): bool
    {
        // TODO: Implement validateAuthKey() method.
    }

    /**
     * Returns first name.
     *
     * @return string user's first name
     */
    public function getFirstName(): string
    {
        return explode(' ', $this->name)[0];
    }

    /**
     * Returns user role.
     *
     * @return string user's role
     */
    public function getRole(): string
    {
        if (count($this->skills)) {
            return UserRole::CONTRACTOR;
        }
        return UserRole::CLIENT;
    }

    public function hasRespondedOnTask($task): bool
    {
        if (Response::find()->where(['user_id' => $this->id])->andWhere(['task_id' => $task->id])->one()) {
            return true;
        }
        return false;
    }

    /**
     * Checks if this user is added to the favorites by a current user.
     *
     * @return bool
     */
    public function isInFavorites(): bool
    {
        $currentUser = Yii::$app->user->identity;
        $favorites = $currentUser->getFavoriteUsers()->asArray()->all();
        $favoritesIds = ArrayHelper::getColumn($favorites, 'favorite_id');

        return ArrayHelper::isIn($this->id, $favoritesIds);

    }

    /**
     * Adds or removes this user form the favorites of a current user.
     *
     */
    public function toggleFavoriteUser()
    {
        $currentUser = Yii::$app->user->identity;
        $favorite = Favorite::find()
            ->where(['user_id' => $currentUser->id,])
            ->andWhere(['favorite_id' => $this->id])
            ->one();

        if ($favorite) {
            $favorite->delete();
        } else {
            $newFavorite = new Favorite();
            $newFavorite->user_id = $currentUser->id;
            $newFavorite->favorite_id = $this->id;
            $newFavorite->save();
        }
    }

    /**
     * Checks if the user has the tasks of the another user.
     *
     * @param User $user
     * @return bool
     */
    public function isWorkingFor(User $user) : bool
    {
        if ($this->getContractorTasks()->andWhere(['client_id' => $user->id])->count()) {
            return true;
        }
        return false;
    }

    /**
     * Returns a number of the user's completed tasks
     * @return int
     */
    public function getNumberOfCompletedTasks() : int
    {
        return $this->getContractorTasks()->where(['status' => TaskStatus::DONE])->count();
    }

}
