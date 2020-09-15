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

    private ?string $address = '';
    private array $autocompletionList = [];
    private ?string $city = '';
    private array $coords = [];

    public function __construct(array $data)
    {
        $this->address = $this->extractAddress($data);
        $this->city = $this->extractCity($data);
        $this->coords = $this->extractCoords($data);
        $this->autocompletionList = $this->extractAutocompletionList($data);

    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getAutocompletionList(): array
    {
        return $this->autocompletionList;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCoords(): array
    {
        return $this->coords;
    }


    private function extractCoords(array $data): array
    {
        $coords = ArrayHelper::getValue($data, self::RESPONSE_KEY_POSITION);
        return $coords ? explode(' ', $coords) : [null, null];
    }


    private function extractAddress(array $data): ?string
    {
        return ArrayHelper::getValue($data, self::RESPONSE_KEY_ADDRESS);
    }


    private function extractAutocompletionList(array $data): array
    {
        $locations = ArrayHelper::getValue($data, self::RESPONSE_KEY_FEATURE_MEMBER);

        return array_reduce($locations, function ($carry, $item) {
            $city = City::findOne(['name' => $this->city]);
            array_push($carry, [
                'address' => ArrayHelper::getValue($item, self::RESPONSE_KEY_ADDRESS_FORMATTED),
                'longitude' => $this->coords[1],
                'latitude' => $this->coords[0],
                'city_id' => $city ? $city->id : null
            ]);
            return $carry;
        }, []);
    }

    private function extractCity(array $data) : string
    {
        $city = '';
        if (is_array($data) && ArrayHelper::getValue($data, self::RESPONSE_KEY_POSITION)) {
            $components = ArrayHelper::getValue($data, self::RESPONSE_KEY_COMPONENTS);
            foreach ($components as $component) {
                if ($component['kind'] === 'locality') {
                    $city = $component['name'];
                }
            }
        }
        return $city;
    }

}
