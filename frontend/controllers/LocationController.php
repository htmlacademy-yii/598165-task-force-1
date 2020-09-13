<?php


namespace frontend\controllers;


use frontend\services\LocationService;
use frontend\services\YandexGeoObject;
use yii\web\Controller;

class LocationController extends Controller
{
    public function actionGetAutocompletionList(string $address)
    {
        if (!$address) {
            return $this->asJson(['error' => 'Empty address']);
        }

        $locationService = new LocationService();
        $location = $locationService->getLocation($address);

        return $this->asJson($location->getAutocompletionList());
    }

}
