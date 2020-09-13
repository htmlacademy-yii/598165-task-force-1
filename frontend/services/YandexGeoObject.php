<?php


namespace frontend\services;


use frontend\models\City;
use yii\helpers\ArrayHelper;

class YandexGeoObject implements GeoObjectInterface
{

    const RESPONSE_KEY_POSITION = 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos';
    const RESPONSE_KEY_COMPONENTS = 'response.GeoObjectCollection.featureMember.0.GeoObject.metaDataProperty.GeocoderMetaData.Address.Components';
    const RESPONSE_KEY_ADDRESS = 'response.GeoObjectCollection.featureMember.0.GeoObject.name';
    const RESPONSE_KEY_FEATURE_MEMBER = 'response.GeoObjectCollection.featureMember';
    const RESPONSE_KEY_ADDRESS_FORMATTED = 'GeoObject.metaDataProperty.GeocoderMetaData.Address.formatted';

    private array $data = [];

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData() :array
    {
        return $this->data;
    }

    public function getCity() : string
    {
        $city = '';
        if (is_array($this->data) && ArrayHelper::getValue($this->data, self::RESPONSE_KEY_POSITION)) {
            $components = ArrayHelper::getValue($this->data, self::RESPONSE_KEY_COMPONENTS);
            foreach ($components as $component) {
                if ($component['kind'] === 'locality') {
                    $city = $component['name'];
                }
            }
        }
        return $city;
    }


    public function getCoords(): string
    {
        return ArrayHelper::getValue($this->data, self::RESPONSE_KEY_POSITION);
    }


    public function getAddress(): string
    {
        return ArrayHelper::getValue($this->data, self::RESPONSE_KEY_ADDRESS);
    }


    public function getAutocompletionList(): array
    {
        $locations = ArrayHelper::getValue($this->data, self::RESPONSE_KEY_FEATURE_MEMBER);

        return array_reduce($locations, function ($carry, $item) {
            $city = City::findOne(['name' => $this->getCity()]);
            array_push($carry,  [
                'address' => ArrayHelper::getValue($item, self::RESPONSE_KEY_ADDRESS_FORMATTED),
                'longitude' => explode(' ', $this->getCoords())[1],
                'latitude' => explode(' ', $this->getCoords())[0],
                'city_id' =>  $city ? $city->id : null
            ]);
            return $carry;
        }, []);
    }
}
