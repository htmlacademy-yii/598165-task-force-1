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
            ['user', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['email' => 'email']],
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
    public function validatePassword(string $attribute, array $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$this->validate('user') || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
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
