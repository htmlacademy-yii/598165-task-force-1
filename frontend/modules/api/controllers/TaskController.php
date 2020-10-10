<?php


namespace frontend\modules\api\controllers;


use frontend\models\Task;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

class TaskController extends ActiveController
{
    public $modelClass = Task::class;

    public function actions()
    {
        $actions =  parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex() {
        return new ActiveDataProvider([
            'query' => Task::find()->where(['client_id' => \Yii::$app->user->id])
        ]);

    }

}
