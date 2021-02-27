<?php

namespace frontend\controllers;

use frontend\models\Event;
use frontend\models\forms\CreateTaskForm;
use frontend\models\forms\FinishTaskForm;
use frontend\models\forms\ResponseTaskForm;
use frontend\models\Response;
use frontend\models\Review;
use frontend\models\Task;

use frontend\models\forms\TasksFilter;
use frontend\models\User;
use TaskForce\models\TaskStatus;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\NotFoundHttpException;


class TasksController extends SecuredController
{
    const PER_PAGE = 5;

    public function behaviors() : array
    {
        $rules = parent::behaviors();

        $rule = [
            'allow' => false,
            'actions' => ['create'],
            'matchCallback' => function ($rule, $action) {
                $user = User::findOne(Yii::$app->user->getId());
                return count($user->skills) > 0;
            }
        ];
        array_unshift($rules['access']['rules'], $rule);

        $rule = [
            'allow' => false,
            'actions' => ['accept', 'decline'],
            'matchCallback' => function ($rule, $action) {
                $response = Response::findOne(Yii::$app->request->get('id'));
                if ($response) {
                    return $response->task->client_id !== Yii::$app->user->getId();
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
                $task = Task::findOne(Yii::$app->request->get('id'));
                if ($task) {
                    return $task->contractor_id !== Yii::$app->user->getId();
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
                $task = Task::findOne(Yii::$app->request->get('id'));
                if ($task) {
                    return $task->client_id !== Yii::$app->user->getId();
                }
                return true;
            }
        ];
        array_unshift($rules['access']['rules'], $rule);

        $rule = [
            'allow' => false,
            'actions' => ['response'],
            'matchCallback' => function ($rule, $action) {
                $user = User::findOne(Yii::$app->user->getId());
                return !count($user->skills);
            }
        ];
        array_unshift($rules['access']['rules'], $rule);


        return $rules;
    }

    /**
     * Displays a page with the tasks list.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;

        if (!isset($session['currentCity'])) {
            $session['currentCity'] = Yii::$app->user->identity->city_id;
        }

        $taskFilter = new TasksFilter();

        $query = Task::find()
            ->where(['status' => TaskStatus::NEW])
            ->with(['city', 'skill', 'responses'])
            ->orderBy(['created_at' => SORT_DESC]);

        $request = Yii::$app->request->get();
        if ($taskFilter->load($request) && $taskFilter->validate()) {
            $query = $taskFilter->applyFilters($query);
        }
        $query = $taskFilter->applyFilters($query);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::PER_PAGE
            ]
        ]);

        $tasks = $provider->getModels();

        return $this->render('index', [
            'tasks' => $tasks,
            'taskFilter' => $taskFilter,
            'pages' => $provider->getPagination()
        ]);
    }

    /**
     * Displays a page with the single task.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {

        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        }

        $responseTaskForm = new ResponseTaskForm($task);
        $finishTaskForm = new FinishTaskForm();

        return $this->render('view', [
            'task' => $task,
            'responseTaskForm' => $responseTaskForm,
            'finishTaskForm' => $finishTaskForm,
        ]);
    }

    /**
     * Creates a new task.
     * @return string
     * @throws Throwable
     */
    public function actionCreate(): string
    {
        $createTaskForm = new CreateTaskForm();

        if (Yii::$app->request->getIsPost()) {
            $request = Yii::$app->request->post();


            if ($createTaskForm->load($request) && $createTaskForm->createTask()) {
                $this->redirect(['tasks/view', 'id' => $createTaskForm->newTask->id]);
            }
        }

        return $this->render('create', [
            'createTaskForm' => $createTaskForm,
        ]);

    }

    /**
     * Accepts the contractor's response.
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionAccept(int $id): \yii\web\Response
    {
        $response = Response::findOne($id);

        $task = $response->task;
        $task->status = TaskStatus::PENDING;
        $task->contractor_id = $response->user_id;
        $response->status = Response::ACCEPTED;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($task->save() && $response->save()) {
                $event = new Event();
                $event->type = Event::START_TASK;
                $event->task_id = $task->id;
                if ($event->save()) {
                    $event->notify(Event::TYPE[Event::START_TASK]);
                }
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            $transaction->rollBack();

        }

        return $this->goHome();


    }

    /**
     * Declines the contractor's response.
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionDecline(int $id): \yii\web\Response
    {
        $response = Response::findOne($id);

        $task = $response->task;
        $response->status = Response::DECLINED;
        $response->save();

        return $this->redirect(['tasks/view', 'id' => $task->id]);

    }

    /**
     * Rejects the tasks.
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionReject(int $id): \yii\web\Response
    {
        $task = Task::findOne($id);
        $user = User::findOne(['id' => Yii::$app->user->getId()]);
        $task->status = TaskStatus::FAILED;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($task->save()) {
                $event = new Event();
                $event->type = Event::REJECT_TASK;
                $event->task_id = $task->id;

                if ($event->save()) {
                    $event->notify(Event::TYPE[Event::REJECT_TASK]);
                }
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
        }

        return $this->goHome();
    }

    /**
     * Finishes the task.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionFinish(int $id): \yii\web\Response
    {
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        }

        $responseTaskForm = new ResponseTaskForm($task);
        $finishTaskForm = new FinishTaskForm();


        if (Yii::$app->request->isAjax) {
            if ($finishTaskForm->load(Yii::$app->request->post()) && $finishTaskForm->validate()) {
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

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($task->save() && $review->save()) {

                        $event = new Event();
                        $event->type = Event::FINISH_TASK;
                        $event->task_id = $task->id;

                        if ($event->save()) {
                            $event->notify(Event::TYPE[Event::FINISH_TASK]);
                        }
                        $transaction->commit();
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }

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

    /**
     * Cancels the task.
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionCancel(int $id): \yii\web\Response
    {
        $task = Task::findOne($id);
        $task->status = TaskStatus::CANCELED;
        $task->save();
        return $this->goHome();
    }

    /**
     * Responses on the task.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionResponse(int $id): \yii\web\Response
    {
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        }

        $responseTaskForm = new ResponseTaskForm($task);

        if (Yii::$app->request->getIsPost()) {
            $request = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();

            if ($responseTaskForm->load($request) && $responseTaskForm->createResponse()) {

                $event = new Event();
                $event->type = Event::NEW_RESPONSE;
                $event->task_id = $task->id;

                if ($event->save()) {
                    $event->notify(Event::TYPE[Event::NEW_RESPONSE]);
                } else {
                    $transaction->rollBack();
                }
                $transaction->commit();
                return $this->redirect(['tasks/view', 'id' => $task->id]);
            } else {
                $transaction->rollBack();
            }
        }
        return $this->goHome();
    }

    /**
     * Shows a page with the personal tasks.
     * @param string $filter
     * @return string
     */
    public function actionPersonal(string $filter = ''): string
    {

        $tasks = Task::getPersonalTasks($filter);

        return $this->render('personal', [
            'tasks' => $tasks,
            'currentFilter' => $filter
        ]);
    }

}

