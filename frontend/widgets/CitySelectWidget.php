<?php


namespace frontend\widgets;


use frontend\models\City;
use yii\base\Widget;

class CitySelectWidget extends Widget
{
    private ?int $currentCity = null;

    public function init()
    {
        parent::init();

        $session = \Yii::$app->session;
        if (isset($session['currentCity'])) {
            $this->currentCity = $session['currentCity'];
        } else {
            $this->currentCity = \Yii::$app->user->identity->city_id;
        }
    }

    public function run()
    {
        $cities = City::find()->asArray()->all();

        return $this->render('cities', [
            'currentCity' => $this->currentCity,
            'cities' => $cities,
        ]) ;
    }
}
