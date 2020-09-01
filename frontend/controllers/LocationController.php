<?php


namespace frontend\controllers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Yii;
use yii\web\Controller;

class LocationController extends Controller
{
    public function actionIndex($address)
    {
        $responseData = null;

            $apiKey = Yii::$app->params['apiKey'];
            $apiUrl = 'https://geocode-maps.yandex.ru/1.x';
            $client = new Client();

            if (!$address) {
                return null;
            }

            try {

                $request = new Request('GET', $apiUrl);

                $response = $client->send($request, [
                    'query' => [
                        'apikey' => $apiKey,
                        'lang' => 'ru_RU',
                        'format' => 'json',
                        'geocode' => $address,
                    ],
                ]);

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

            }
        return $this->asJson($responseData);
    }

}
