<?php

namespace frontend\validators;

use frontend\services\LocationService;
use Yii;
use yii\validators\Validator;

class LocationValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $result = false;

        $locationService = new LocationService();
        $location = $locationService->getLocation($model->$attribute);

        $city = $location->getCity();

        if (Yii::$app->user->identity->city->name === $city) {
            $result = true;
        } else {
            $this->addError($model, $attribute, 'Укажите локацию в вашем городе');
        }

        return $result;
    }
}
