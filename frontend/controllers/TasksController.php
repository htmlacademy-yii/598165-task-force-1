<?php

namespace frontend\controllers;

use frontend\models\forms\CreateTaskForm;
use frontend\models\forms\FinishTaskForm;
use frontend\models\forms\ResponseTaskForm;
use frontend\models\Response;
use frontend\models\Review;
use frontend\models\Task;

use frontend\models\forms\TasksFilter;
use frontend\models\User;
use TaskForce\models\TaskStatus;
use yii\web\NotFoundHttpException;


class TasksController extends SecuredController
{
    public function behaviors()
    {
        $rules = parent::behaviors();

        $rule = [
            'allow' => false,
            'actions' => ['create'],
            'matchCallback' => function ($rule, $action) {
                $user = User::findOne(\Yii::$app->user->getId());
                return count($user->skills) > 0;
            }
        ];
        array_unshift($rules['access']['rules'], $rule);

        $rule = [
            'allow' => false,
            'actions' => ['accept', 'decline'],
            'matchCallback' => function ($rule, $action) {
                $response = Response::findOne(\Yii::$app->request->get('id'));
                if ($response) {
                    return $response->task->client_id !== \Yii::$app->user->getId();
                } else {
                    return true;
                }
            }
        ];
        array_unshift($rules['access']['rules'], $rule);


        $rule = [
            'allow' => false,
            'actions' => ['reject'],
            'matchCallback' => function ($rule, $action) {
                $task = Task::findOne(\Yii::$app->request->get('id'));
                if ($task) {
                    return $task->contractor_id !== \Yii::$app->user->getId();
                } else {
                    return true;
                }
            }
        ];
        array_unshift($rules['access']['rules'], $rule);

        $rule = [
            'allow' => false,
            'actions' => ['cancel', 'finish'],
            'matchCallback' => function ($rule, $action) {
            $task = Task::findOne(\Yii::$app->request->get('id'));
            if ($task) {
                return $task->client_id !== \Yii::$app->user->getId();
            }
            return true;
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
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        }

        $responseTaskForm = new ResponseTaskForm($task);
        $finishTaskForm = new FinishTaskForm();

        if (\Yii::$app->request->getIsPost()) {
            $request = \Yii::$app->request->post();
            $finishTaskForm->load($request);


            if ($responseTaskForm->load($request) && $responseTaskForm->createResponse()) {
                return $this->refresh();
            }
        }

        return $this->render('view', [
            'task' => $task,
            'responseTaskForm' => $responseTaskForm,
            'finishTaskForm' => $finishTaskForm,
        ]);
    }

    public function actionCreate()
    {
        $createTaskForm = new CreateTaskForm();

        if (\Yii::$app->request->getIsPost()) {
            $request = \Yii::$app->request->post();


            if ($createTaskForm->load($request) && $createTaskForm->createTask()) {
                return $this->redirect(['tasks/view', 'id' => $createTaskForm->newTask->id]);
            }
        }

        return $this->render('create', [
            'createTaskForm' => $createTaskForm,
        ]);

    }

    public function actionAccept(int $id)
    {
        $response = Response::findOne($id);

        $task = $response->task;
        $task->status = TaskStatus::PENDING;
        $task->contractor_id = $response->user_id;
        $task->save();

        $response->status = Response::ACCEPTED;
        $response->save();

        return $this->goHome();


    }

    public function actionDecline(int $id)
    {
        $response = Response::findOne($id);

        $task = $response->task;
        $response->status = Response::DECLINED;
        $response->save();

        return $this->redirect(['tasks/view', 'id' => $task->id]);

    }

    public function actionReject(int $id)
    {
        $task = Task::findOne($id);
        $user = User::findOne(['id' => \Yii::$app->user->getId()]);
        $task->status = TaskStatus::FAILED;
        ++$user->failed_tasks;
        $user->save();
        $task->save();

        return $this->goHome();
    }

    public function actionFinish($id)
    {
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        }

        $responseTaskForm = new ResponseTaskForm($task);
        $finishTaskForm = new FinishTaskForm();


        if (\Yii::$app->request->isAjax) {
            if ($finishTaskForm->load(\Yii::$app->request->post()) && $finishTaskForm->validate()) {
                $review = new Review();

                if ($finishTaskForm->completion === FinishTaskForm::COMPLETION_PROBLEMS) {
                    $task->status = TaskStatus::FAILED;
                } else {
                    $task->status = TaskStatus::DONE;
                }

                $review->task_id = $task->id;
                $review->description = $finishTaskForm->comment;
                $review->rating = intval($finishTaskForm->rating);
                $review->user_id = $task->contractor_id;

                $task->save();
                $review->save();

                $this->redirect(['/tasks']);
                return $this->asJson(['success' => true, 'validationErrors' => false]);
            }

            return $this->asJson(['success' => false, 'validationErrors' => true]);
        }


        return $this->render('view', [
            'task' => $task,
            'responseTaskForm' => $responseTaskForm,
            'finishTaskForm' => $finishTaskForm,
        ]);
    }

    public function actionCancel($id) {
        $task = Task::findOne($id);
        $task->status = TaskStatus::CANCELED;
        $task->save();
        return $this->goHome();
    }

}

