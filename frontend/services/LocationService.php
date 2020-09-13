<?php

namespace frontend\services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Yii;

class LocationService
{

    private string $url;
    private array $query;
    private GeoObjectInterface $geoObject;


    public function __construct()
    {

        $this->url = Yii::$app->locationProvider->getUrl();
        $this->query = Yii::$app->locationProvider->getQuery();

        $this->geoObject = new YandexGeoObject();
    }

    public function getLocation(string $address) : ?GeoObjectInterface
    {

        $client = new Client();
        $this->query[Yii::$app->locationProvider->addressKeyName] = $address;
        $responseData = null;

        try {

            $request = new Request('GET', $this->url);
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
            $this->geoObject->setData($responseData);

        } catch (RequestException $e) {
        } catch (GuzzleException $e) {
            return null;
        }
        return  $this->geoObject;
    }

}
