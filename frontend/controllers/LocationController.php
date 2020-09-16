<?php


namespace frontend\controllers;


use yii\web\Controller;

class LocationController extends Controller
{
    public function actionGetAutocompletionList(string $address)
    {
        if (!$address) {
            return $this->asJson(['error' => 'Empty address']);
        }

        $location =\Yii::$app->locationService->getLocation($address);

        if (!$location) {
            return $this->asJson([]);
        }

        return $this->asJson($location->getAutocompletionList());
    }

}
