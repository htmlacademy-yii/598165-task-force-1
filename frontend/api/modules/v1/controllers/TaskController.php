<?php


namespace frontend\api\modules\v1\controllers;


use frontend\api\modules\v1\resources\Task;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;


class TaskController extends ActiveController
{
    public $modelClass = Task::class;

    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['view']);
        unset($actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $this->modelClass::find()->where(['client_id' => Yii::$app->user->id]),
            'pagination' => false
        ]);

    }

}
