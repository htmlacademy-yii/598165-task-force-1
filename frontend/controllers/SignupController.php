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

        if ($signupForm->load(\Yii::$app->request->post()) && $signupForm->signup()) {
            return $this->goHome();
        }
        return $this->render('index', ['signupForm' => $signupForm]);
    }

}
