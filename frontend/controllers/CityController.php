<?php


namespace frontend\controllers;


use yii\db\ActiveQuery;

class CityController extends SecuredController
{
    static public function applyDefaultCityFilter(ActiveQuery $query)
    {
        $session = \Yii::$app->session;
        if (isset($session['currentCity'])) {
            $query->andWhere(['city_id' => $session['currentCity']]);
        } else {
            $query->andWhere(['city_id' => \Yii::$app->user->identity->city_id]);
            $query->orWhere(['city_id' => null]);
        }
    }

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
