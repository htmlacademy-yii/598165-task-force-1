<?php


namespace frontend\modules\api\controllers;


use frontend\models\Message;
use frontend\models\Task;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use yii\web\ServerErrorHttpException;

class MessageController extends ActiveController
{
    public $modelClass = Message::class;


    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['create']);
        return $actions;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function ($rule, $action) {
                    return $this->asJson([]);
                },
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $task = Task::findOne(\Yii::$app->request->get('id'));
                            if ($task) {
                                return $task->client_id === \Yii::$app->user->getId() || $task->contractor_id === \Yii::$app->user->getId();
                            }
                            return false;
                        }
                    ]
                ]
            ]
        ];
    }

    public function actionView($id)
    {

        $messages = Message::find()->where(['task_Id' => $id])->all();
        return $this->asJson($messages);

    }

    public function actionCreate()
    {
        $request = \Yii::$app->request;
        $message = new $this->modelClass;

        $message->text = $request->post('message');
        $message->task_id = $request->post('task_id');
        $message->user_id = \Yii::$app->user->getId();
        $message->created_at = date('Y-d-m h:i:s');

        if ($message->save()) {
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(201);

        } elseif (!$message->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $message;
    }


}
