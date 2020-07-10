<?php


namespace frontend\controllers;


class CityController extends SecuredController
{

    public function actionIndex()
    {
        if (\Yii::$app->request->getIsPost()) {
            $city = \Yii::$app->request->post('town');

            $session = \Yii::$app->session;
            $session['currentCity'] = $city;

            $this->redirect(\Yii::$app->request->getReferrer());

        }
    }

}
