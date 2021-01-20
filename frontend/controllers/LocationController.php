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

        $locationKey = md5($address);
        $cashedAddress = \Yii::$app->cache->get($locationKey);

        if ($cashedAddress) {
            return $this->asJson($cashedAddress->getAutocompletionList());
        }

        $location =\Yii::$app->locationService->getLocation($address);
        \Yii::$app->cache->set($locationKey, $location, 86400);

        if (!$location) {
            return $this->asJson([]);
        }

        return $this->asJson($location->getAutocompletionList());
    }
}
