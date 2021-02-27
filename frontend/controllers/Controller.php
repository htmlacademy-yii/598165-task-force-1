<?php


namespace frontend\controllers;


use frontend\models\User;
use Yii;

class Controller extends \yii\web\Controller
{
    public function init() {
        parent::init();
        if (!Yii::$app->user->isGuest) {
            $user = User::findOne(Yii::$app->user->id);
            $user->last_seen_at = date('Y-m-d H:i:s');
            $user->save(false, ['last_seen_at']);
        }
    }

}
