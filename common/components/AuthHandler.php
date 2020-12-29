<?php

namespace common\components;

use frontend\models\Auth;
use frontend\models\City;
use frontend\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{

    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $attributes = $this->client->getUserAttributes();

        $id = ArrayHelper::getValue($attributes, 'id');
        $email = ArrayHelper::getValue($attributes,'email','');
        $cityName = ArrayHelper::getValue($attributes, 'city.title', '');
        $name = ArrayHelper::getValue($attributes, 'first_name', '' ) . ' ' . ArrayHelper::getValue($attributes, 'last_name', '');
        $city = City::find()->where(['name' => $cityName])->one();
        $avatar = ArrayHelper::getValue($attributes, 'photo');

        if (!$city) {
            throw new NotFoundHttpException('В профиле вконтакте не задан город или задан неизвестный город ' . $cityName);
        }

        $auth = Auth::find()->where([
            'source' => $this->client->getId(),
            'source_id' => $id,
        ])->one();

        if ($auth) {
            $user = $auth->user;
            Yii::$app->user->login($user);
        } else {
            $user = User::find()->where(['email' => $email])->one();
            if ($email !== null && $user) {
                Yii::$app->user->login($user);
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

                if ($user->save()) {
                    $auth = new Auth([
                        'user_id' => $user->id,
                        'source' => $this->client->getId(),
                        'source_id' => (string)$id,
                    ]);
                    if ($auth->save()) {
                        $transaction->commit();
                        Yii::$app->user->login($user);
                    } else {
                        throw new Exception('Unable to save user');
                    }
                } else {
                    throw new Exception('Unable to save user');
                }
            }
        }

    }

}
