<?php
namespace frontend\controllers;
use frontend\models\Task;
use yii\db\Query;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {

        $tasks = Task::find()
            ->with('city')
            ->with('skill')
            ->where(['status' => 'NEW'])
            ->orderBy(['created_at' => SORT_ASC])
            ->all();

        return $this->render('index', ['tasks' => $tasks]);
    }
}

