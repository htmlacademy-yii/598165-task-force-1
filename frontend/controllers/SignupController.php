<?php


namespace frontend\controllers;


use frontend\models\SignupForm;
use yii\web\Controller;

class SignupController extends Controller
{
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
