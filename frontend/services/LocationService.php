<?php

namespace frontend\services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Yii;
use yii\helpers\ArrayHelper;

class LocationService
{
    const apiUrl = 'https://geocode-maps.yandex.ru/1.x';

    private array $query = [];
    private ?array $responseData = null;

    const responseKeyPosition = 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos';
    const responseKeyComponents = 'response.GeoObjectCollection.featureMember.0.GeoObject.metaDataProperty.GeocoderMetaData.Address.Components';
    const responseKeyAddress = 'response.GeoObjectCollection.featureMember.0.GeoObject.name';
    const responseKeyFeatureMember = 'response.GeoObjectCollection.featureMember';
    const responseKeyAddressFormatted = 'GeoObject.metaDataProperty.GeocoderMetaData.Address.formatted';

    function __construct(string $address)
    {

        $this->query['apikey'] = Yii::$app->params['apiKey'];
        $this->query['lang'] = 'ru_RU';
        $this->query['format'] = 'json';
        $this->query['geocode'] = $address;

        $this->responseData = $this->makeRequest();
    }

    private function makeRequest()
    {

        $client = new Client();
        $responseData = null;

        try {

            $request = new Request('GET', self::apiUrl);
            $response = $client->send($request, ['query' => $this->query]);

            if ($response->getStatusCode() !== 200) {
                throw new BadResponseException('Response error: ' . $response->getReasonPhrase(), $request,
                    $response);
            }

            $content = $response->getBody()->getContents();
            $responseData = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ServerException('Invalid json format', $request, $response);
            }

        } catch (RequestException $e) {
        } catch (GuzzleException $e) {
            return null;
        }
        return $responseData;
    }

    public function getData()
    {
        return $this->responseData;
    }

    public function getCity(): string
    {
        $city = null;
        if (is_array($this->responseData) && ArrayHelper::getValue($this->responseData, self::responseKeyPosition)) {
            $components = ArrayHelper::getValue($this->responseData, self::responseKeyComponents);
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
        return ArrayHelper::getValue($this->responseData, self::responseKeyPosition);
    }


    public function getAddress(): string
    {
        return ArrayHelper::getValue($this->responseData, self::responseKeyAddress);
    }


    public function getAutocompletionList(): array
    {
        $locations = ArrayHelper::getValue($this->responseData, self::responseKeyFeatureMember);

        return array_reduce($locations, function ($carry, $item) {
            array_push($carry,  ArrayHelper::getValue($item, self::responseKeyAddressFormatted));
            return $carry;
        }, []);
    }

}
