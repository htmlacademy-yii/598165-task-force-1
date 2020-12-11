<?php

namespace frontend\models\forms;

use Exception;
use frontend\models\City;
use frontend\models\File;
use frontend\models\Skill;
use frontend\models\User;
use frontend\models\UserHasSkill;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for form "Settings Form".
 *
 * @property string $email
 * @property string|null $avatar
 * @property int $city_id
 * @property string|null $about
 * @property string|null $birthday_at
 * @property string $password_new
 * @property string $password_repeat
 * @property string|null $phone
 * @property string|null $skypeid
 * @property string|null $messenger
 * @property int $is_notify_message
 * @property int $is_notify_action
 * @property int $is_notify_review
 * @property int $is_show_only_owner
 * @property int $is_hidden
 *
 * @property  $skills
 * @property array $files
 */
class SettingsForm extends Model
{
    public string $email;
    public ?string $avatar;
    public int $city_id;
    public ?string $about;
    public ?string $birthday_at;
    public string $password_new;
    public string $password_repeat;
    public ?string $phone;
    public ?string $skypeid;
    public ?string $messenger;
    public int $is_notify_message;
    public int $is_notify_action;
    public int $is_notify_review;
    public int $is_show_only_owner;
    public int $is_hidden;

    public $skills;
    public array $files;

    private ?User $user;

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->user = User::findOne(\Yii::$app->user->id);

        $this->email = $this->user->email;
        $this->avatar = $this->user->avatar;
        $this->city_id = $this->user->city_id;
        $this->about = $this->user->about;
        $this->birthday_at = $this->user->birthday_at;
        $this->password_new = '';
        $this->password_repeat = '';
        $this->phone = $this->user->phone;
        $this->skypeid = $this->user->skypeid;
        $this->messenger = $this->user->messenger;
        $this->is_notify_message = $this->user->is_notify_message;
        $this->is_notify_action = $this->user->is_notify_action;
        $this->is_notify_review = $this->user->is_notify_review;
        $this->is_show_only_owner = $this->user->is_show_only_owner;
        $this->is_hidden = $this->user->is_hidden;

        $this->skills = $this->user->skills;
        $this->files = [];

    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['avatar', 'about', 'password_repeat', 'password_new'], 'string'],
            ['avatar', 'default', 'value' => null],
            [['email', 'city_id'], 'required'],
            ['password_new', 'string', 'min' => 8, 'tooShort' => 'Короткий пароль'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password_new', 'message' => 'Введены разные пароли'],

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

            [['birthday_at', 'created_at', 'last_seen_at'], 'safe'],
            [['email'], 'string', 'max' => 320],
            [['phone'], 'string', 'max' => 11],
            [['skypeid', 'messenger'], 'string', 'max' => 255],
            [
                ['email'],
                'unique',
                'targetClass' => User::class,
                'targetAttribute' => 'email',
                'when' => function () {
                    return $this->user->isAttributeChanged('email');
                }
            ],
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
                'skipOnEmpty' => true,
                'targetClass' => Skill::class,
                'targetAttribute' => 'id'
            ],
            ['skills', 'default', 'value' => []],
        ];
    }

    /**
     * Save settings.
     *
     * @return bool whether saving was successful
     */
    public function save(): bool
    {

        if (!$this->validate()) {
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $this->saveAvatar();
            $this->user->email = $this->email;
            $this->user->city_id = $this->city_id;
            $this->user->about = $this->about;
            $this->user->birthday_at = $this->birthday_at;

            $this->user->phone = $this->phone;
            $this->user->skypeid = $this->skypeid;
            $this->user->messenger = $this->messenger;
            $this->user->is_notify_message = $this->is_notify_message;
            $this->user->is_notify_action = $this->is_notify_action;
            $this->user->is_notify_review = $this->is_notify_review;
            $this->user->is_show_only_owner = $this->is_show_only_owner;
            $this->user->is_hidden = $this->is_hidden;
            $this->user->skills = $this->skills;
            $this->saveNewPassword();

            if (!$this->user->save()) {
                return false;
            }

            $this->saveSkills();

            $transaction->commit();
            \Yii::$app->session['currentCity'] = $this->user->city_id;

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return true;
    }

    private function saveSkills()
    {
        UserHasSkill::deleteAll(['user_id' => $this->user->id]);

        foreach ($this->skills as $skill_id) {
            $relation = new UserHasSkill();
            $relation->user_id = $this->user->id;
            $relation->skill_id = $skill_id;

            if (!$relation->save()) {
                throw new Exception('Failed to save related records');
            }
        }
    }

    private function saveAvatar()
    {
        if (!empty(UploadedFile::getInstances($this, 'avatar'))) {
            $avatar = UploadedFile::getInstances($this, 'avatar')[0];

            $src = \Yii::getAlias('@webroot/uploads') . '/avatar' . $this->user->id;

            FileHelper::removeDirectory($src);
            FileHelper::createDirectory($src);

            $avatar->saveAs($src . "/$avatar->name");

            $this->user->avatar = '/uploads/avatar' . $this->user->id . "/$avatar->name";
        }
    }

    private function saveNewPassword()
    {
        if (isset($this->password_new)) {
            $this->user->password = \Yii::$app->getSecurity()->generatePasswordHash($this->password_new);
            $this->user->save();
        }

    }

}
