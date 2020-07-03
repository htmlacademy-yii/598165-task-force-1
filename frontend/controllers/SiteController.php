<?php

namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;


/**
 * Site controller
 */
class SiteController extends SecuredController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function($rule, $action) {
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
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
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
            if ($loginForm->load(Yii::$app->request->post()) && $loginForm->validate()) {
                $user = $loginForm->getUser();
                \Yii::$app->user->login($user);

                \Yii::$app->view->params['currentUser'] = $user;

                $this->redirect(['/tasks']);
                return $this->asJson(['success' => true]);
            }

            return $this->asJson(['validation' => ActiveForm::validate($loginForm)]);
        }

        return $this->render('index', ['loginForm' => $loginForm]);
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
