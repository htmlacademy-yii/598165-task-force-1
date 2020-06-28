<?php


namespace frontend\models;


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
            ['password', 'validatePassword']
        ];
    }

    public function ValidatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email]);
        }
        return $this->_user;
    }
}
