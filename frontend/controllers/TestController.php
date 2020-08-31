<?php


namespace frontend\controllers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $geocode = Yii::$app->request->get('address');
        $apiKey = Yii::$app->params['apiKey'];
        $apiUrl = 'https://geocode-maps.yandex.ru/1.x';
        $responseKey = 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos';
        $client = new Client();
        $result = null;

        if (!$geocode) {
            return null;
        }

        try {

            $request = new Request('GET', $apiUrl);

            $response = $client->send($request, [
                'query' => [
                    'apikey' => $apiKey,
                    'lang' => 'ru_RU',
                    'format' => 'json',
                    'geocode' => $geocode,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new BadResponseException('Response error: ' . $response->getReasonPhrase(), $request, $response);
            }

            $content = $response->getBody()->getContents();
            $responseData = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ServerException('Invalid json format', $request, $response);
            }

            if (is_array($responseData) && ArrayHelper::getValue($responseData, $responseKey)) {
//                $result = ArrayHelper::getValue($responseData, $responseKey);
                $result = true;
            }

        } catch (RequestException $e) {
        } catch (GuzzleException $e) {

        }

        var_dump($result);
        return $result;

    }

}
