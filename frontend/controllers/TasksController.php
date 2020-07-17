<?php

namespace frontend\controllers;

use frontend\models\forms\CreateTaskForm;
use frontend\models\Task;

use frontend\models\forms\TasksFilter;
use frontend\models\User;
use TaskForce\models\TaskStatus;
use yii\web\NotFoundHttpException;


class TasksController extends SecuredController
{
    public function behaviors()
    {
        $rules =  parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['create'],
            'matchCallback' => function ($rule, $action) {
                $user = User::findOne(\Yii::$app->user->getId());
                return count($user->skills) > 0;
            }
        ];
        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function actionIndex()
    {
        $session = \Yii::$app->session;

        if (!isset($session['currentCity'])) {
            $session['currentCity'] = \Yii::$app->user->identity->city_id;
        }

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
        } else {
            $query = $taskFilter->applyFilters($query);
        }

        $tasks = $query->all();

        return $this->render('index', [
            'tasks' => $tasks,
            'taskFilter' => $taskFilter,
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
        ]);
    }

    public function actionCreate()
    {
        $createTaskForm = new CreateTaskForm();

        if (\Yii::$app->request->getIsPost()) {
            $request = \Yii::$app->request->post();


            if ($createTaskForm->load($request)  && $createTaskForm->createTask()) {
                return $this->redirect(['tasks/view', 'id' => $createTaskForm->newTask->id]);
            }
        }

        return $this->render('create', [
                'createTaskForm' => $createTaskForm,
            ]);


    }

}

