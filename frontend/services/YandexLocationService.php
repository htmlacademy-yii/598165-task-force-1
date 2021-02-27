<?php

namespace frontend\services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use yii\base\Component;
use yii\web\ServerErrorHttpException;


class YandexLocationService extends Component implements LocationService
{

    public string $url;
    public ?string $apiKey;
    public string $format;
    public string $lang;

    public function __construct($config = [])
    {
        parent::__construct($config);

        if (!isset($this->apiKey)) {
            throw new ServerErrorHttpException('Location service api key is not set');
        }
    }

    public function getLocation(string $address) : ?GeoObjectInterface
    {

        $client = new Client();
        $responseData = null;

        try {

            $request = new Request('GET', $this->url);
            $response = $client->send($request, ['query' => [
                'apikey' => $this->apiKey,
                'format' => $this->format,
                'geocode' => $address,
                'lang' => $this->lang,
            ]]);

            if ($response->getStatusCode() !== 200) {
                throw new BadResponseException('Response error: ' . $response->getReasonPhrase(), $request,
                    $response);
            }

            $content = $response->getBody()->getContents();
            $responseData = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ServerException('Invalid json format', $request, $response);
            }
            $geoObject = new YandexGeoObject($responseData);

        } catch (RequestException $e) {
        } catch (GuzzleException $e) {
            return null;
        }
        return  $geoObject;
    }

}
