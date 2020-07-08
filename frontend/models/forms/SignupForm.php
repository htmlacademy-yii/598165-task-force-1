<?php
namespace frontend\models\forms;

use Yii;
use yii\base\Model;



/**
 * Signup form
 */
class SignupForm extends Model
{
    public string $username = '';
    public string $email = '';
    public string $city_id = '';
    public string $password = '';


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => 'Имя не задано'],

            ['email', 'trim'],
            ['email', 'required', 'message' => 'Адрес не задан'],
            ['email', 'email', 'message' => 'Неверный адрес'],
            ['email', 'unique', 'targetClass' => User::class,
                'message' => 'Этот почтовый адрес уже занят.'],

            ['password', 'string', 'min' => 8, 'tooShort' => 'Короткий пароль'],
            ['password', 'required', 'message' => 'Пароль не задан'],

            ['city_id', 'required', 'message' => 'Город не задан'],
            ['city_id', 'exist',
                'targetClass' => City::class,
                'targetAttribute' => 'id',
                'message' => 'Неверный город'
            ]
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful
     */
    public function signup() : ?bool
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->name = $this->username;
        $user->email = $this->email;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $user->city_id = intval($this->city_id);

        return $user->save();
    }
}
