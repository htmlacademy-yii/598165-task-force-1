<?php


namespace frontend\controllers;


use frontend\models\SignupForm;
use yii\filters\AccessControl;

class SignupController extends SecuredController
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
     * Displays sign up page.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $signupForm = new SignupForm();


        if (\Yii::$app->request->getIsPost()) {
            $request = \Yii::$app->request->post();

            if ($signupForm->load($request) && $signupForm->signup()) {
                return $this->goHome();
            }
        }

        return $this->render('index', ['signupForm' => $signupForm]);
    }

}
