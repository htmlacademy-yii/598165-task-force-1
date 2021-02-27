<?php

namespace frontend\controllers;

use frontend\models\forms\LoginForm;
use frontend\models\Task;
use TaskForce\models\TaskStatus;
use Yii;
use yii\filters\AccessControl;

use common\components\AuthHandler;

/**
 * Site controller
 */
class SiteController extends SecuredController
{
    public function behaviors() : array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function ($rule, $action) {
                    $this->redirect(['/tasks']);
                },
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        (new AuthHandler())->socialLogin($client);
    }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'landing';
        $loginForm = new LoginForm();

        if (Yii::$app->request->isAjax) {
            if ($loginForm->load(Yii::$app->request->post()) && (new AuthHandler())->userLogin($loginForm)) {

                $this->redirect(['/tasks']);
                return $this->asJson(['success' => true, 'validationErrors' => false]);
            }

            return $this->asJson(['success' => false, 'validationErrors' => true]);
        }

        $recentTasks = Task::find()
            ->where(['status' => TaskStatus::NEW])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(4)
            ->all();

        return $this->render('index', [
            'loginForm' => $loginForm,
            'recentTasks' => $recentTasks
        ]);
    }


    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
