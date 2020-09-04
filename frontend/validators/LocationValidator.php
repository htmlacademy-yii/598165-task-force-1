<?php

namespace frontend\validators;

use frontend\models\City;
use frontend\services\LocationService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\Validator;

class LocationValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $result = false;

        $location = new LocationService($model->$attribute);

            $city = $location->getCity();

            if (Yii::$app->user->identity->city->name === $city) {
                $result = true;
                $coords = $location->getCoords();
                $address = $location->getAddress();

                $model->latitude = floatval(explode(' ', $coords)[0]);
                $model->longitude = floatval(explode(' ', $coords)[1]);
                $model->location = $address;
                $model->city_id = Yii::$app->user->identity->city_id;
            } else {
                $this->addError($model, $attribute, 'Укажите локацию в вашем городе');
            }

        return $result;
    }
}
