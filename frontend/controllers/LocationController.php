<?php


namespace frontend\controllers;


use frontend\services\LocationService;
use yii\web\Controller;

class LocationController extends Controller
{
    public function actionIndex(string $address)
    {
        if (!$address) {
            return  null;
        }

       $location = new LocationService($address);
       return $this->asJson($location->getAutocompletionList());
    }

}
