<?php

namespace frontend\services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;


class YandexLocationService implements LocationService
{

    public string $url;
    public string $apiKey;
    public string $format;
    public string $lang;


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
