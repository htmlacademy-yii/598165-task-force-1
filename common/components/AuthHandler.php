<?php

namespace common\components;

use frontend\models\Auth;
use frontend\models\City;
use frontend\models\forms\LoginForm;
use frontend\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\UnauthorizedHttpException;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    /**
     * Login with VK.
     * @param ClientInterface $client
     * @throws Exception
     * @throws UnauthorizedHttpException
     * @throws \yii\base\Exception
     */
    public function socialLogin(ClientInterface $client)
    {
        $attributes = $client->getUserAttributes();

        $id = ArrayHelper::getValue($attributes, 'id');
        $email = ArrayHelper::getValue($attributes, 'email', '');
        $cityName = ArrayHelper::getValue($attributes, 'city.title', '');
        $name = ArrayHelper::getValue($attributes, 'first_name', '') . ' ' . ArrayHelper::getValue($attributes,
                'last_name', '');
        $city = City::find()->where(['name' => $cityName])->one();
        $avatar = ArrayHelper::getValue($attributes, 'photo');

        if (!$city) {
            throw new UnauthorizedHttpException('В профиле вконтакте не задан город или задан неизвестный город ' . $cityName);
        }

        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $id,
        ])->one();

        if ($auth) {
            $user = $auth->user;
            $this->login($user);
        } else {
            $user = User::find()->where(['email' => $email])->one();
            if ($email !== null && $user) {
                $this->login($user);
            } else {
                $password = Yii::$app->security->generateRandomString(8);
                $user = new User([
                    'name' => $name,
                    'email' => $email,
                    'password' => Yii::$app->getSecurity()->generatePasswordHash($password),
                    'city_id' => $city->id,
                    'avatar' => $avatar
                ]);

                $transaction = User::getDb()->beginTransaction();

                if (!$user->save()) {
                    throw new Exception('Unable to save user');
                }

                $auth = new Auth([
                    'user_id' => $user->id,
                    'source' => $client->getId(),
                    'source_id' => (string)$id,
                ]);

                if (!$auth->save()) {
                    throw new Exception('Unable to save user');
                }

                $transaction->commit();
                $this->login($user);
            }
        }

    }

    /**
     * Login with email and password.
     * @param LoginForm $loginForm
     * @return bool
     */
    public function userLogin(LoginForm $loginForm): bool
    {
        if (!$loginForm->validate()) {
            return false;
        }
        $user = $loginForm->getUser();
        $this->login($user);
        return true;
    }

    /**
     * User login.
     * @param $user
     */
    private function login($user)
    {
        Yii::$app->user->login($user);
    }

}
