<?php

namespace frontend\models;

use Yii;

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
 */
class User extends \yii\db\ActiveRecord
{
    private $_rating;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role', 'name', 'avatar', 'address', 'about', 'password'], 'string'],
            [['email', 'name', 'city_id', 'password'], 'required'],
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
                'targetClass' => City::className(),
                'targetAttribute' => ['city_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
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
     * @return \yii\db\ActiveQuery
     */
    public function getFavoriteUsers()
    {
        return $this->hasMany(Favorite::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Favorites0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsersHaveAddedToFavorites()
    {
        return $this->hasMany(Favorite::className(), ['favorite_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientTasks()
    {
        return $this->hasMany(Task::className(), ['client_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContractorTasks()
    {
        return $this->hasMany(Task::className(), ['contractor_id' => 'id']);
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
     * Gets query for [[UserHasSkills]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasSkills()
    {
        return $this->hasMany(UserHasSkill::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Skills]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkills()
    {
        return $this->hasMany(Skill::className(), ['id' => 'skill_id'])
            ->viaTable('user_has_skill', ['user_id' => 'id']);
    }

    public function setRating($rating)
    {
        $this->_rating = (float)$rating;
    }

    public function getRating()
    {
        if ($this->_rating === null) {
            if (count($this->reviews)) {
                $totalRating = 0;
                foreach ($this->reviews as $review) {
                    $totalRating += $review->rating;
                }
                $this->setRating($totalRating / count($this->reviews));
            } else {
                $this->setRating(0);;
            }
        }

        return $this->_rating;

    }
}
