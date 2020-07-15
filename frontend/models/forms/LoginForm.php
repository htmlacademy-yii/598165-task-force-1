<?php


namespace frontend\models\forms;


use frontend\models\User;
use yii\base\Model;

class LoginForm extends Model
{
    public string $email = '';
    public string $password = '';

    private ?User $_user = null;


    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [
                'email',
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => ['email' => 'email'],
            ],
            ['password', 'validatePassword']
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
        ];
    }

    /**
     * Validates password
     * @param string $attribute
     * @param array $params
     */
    public function validatePassword(string $attribute, ?array $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user->validatePassword($this->password)) {
                $this->addError($attribute);
            }
        }
    }


    /**
     * Returns user
     *
     * @return User user
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email]);
        }
        return $this->_user;
    }
}
