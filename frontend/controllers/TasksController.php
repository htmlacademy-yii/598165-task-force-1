<?php

namespace frontend\controllers;

use frontend\models\Task;

use frontend\models\TasksFilter;
use TaskForce\models\TaskStatus;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $taskFilter = new TasksFilter();
        $query = Task::find()
            ->where(['status' => TaskStatus::NEW])
            ->with(['city', 'skill', 'responses'])
            ->orderBy(['created_at' => SORT_DESC]);

        if (\Yii::$app->request->getIsPost()) {
            $request = \Yii::$app->request->post();

            if ($taskFilter->load($request) && $taskFilter->validate()) {
                $query = $taskFilter->applyFilters($query);
            }
        }

        $tasks = $query->all();

        return $this->render('index', [
            'tasks' => $tasks,
            'taskFilter' => $taskFilter,
        ]);
    }
}

