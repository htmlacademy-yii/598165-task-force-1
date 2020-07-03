<?php

namespace frontend\controllers;

use frontend\models\City;
use frontend\models\Task;

use frontend\models\TasksFilter;
use TaskForce\models\TaskStatus;
use yii\web\NotFoundHttpException;

class TasksController extends SecuredController
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
            'cities' => City::find()->asArray()->all(),
        ]);
    }

    public function actionView(int $id)
    {

        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найден");
        }

        return $this->render('view', [
            'task' => $task,
            'cities' => City::find()->asArray()->all(),
        ]);
    }

}

