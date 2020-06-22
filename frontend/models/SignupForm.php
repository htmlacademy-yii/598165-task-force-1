<?php
namespace frontend\models;

use Yii;
use yii\base\Model;


/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $city;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],

            ['email', 'trim'],
            ['email', 'required', 'message' => 'Введите валидный адрес электронной почты'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class,
                'message' => 'Этот почтовый адрес уже занят.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 8],

            ['city', 'required'],
            ['city', 'exist',
                'targetClass' => City::class,
                'targetAttribute' => 'name'
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
        $user->password = $this->password;
        $user->city_id = City::findOne(['name' => $this->city])->id;

        return $user->save();
    }
}
