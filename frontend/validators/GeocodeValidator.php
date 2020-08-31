<?php

namespace frontend\validators;

use frontend\models\City;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\Validator;

class GeocodeValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $apiKey = Yii::$app->params['apiKey'];
        $apiUrl = 'https://geocode-maps.yandex.ru/1.x';
        $responseKeyPosition = 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos';
        $responseKeyComponents = 'response.GeoObjectCollection.featureMember.0.GeoObject.metaDataProperty.GeocoderMetaData.Address.Components';
        $responseKeyAddress = 'response.GeoObjectCollection.featureMember.0.GeoObject.name';
        $client = new Client();
        $result = null;


        try {

            $request = new Request('GET', $apiUrl);

            $response = $client->send($request, [
                'query' => [
                    'apikey' => $apiKey,
                    'lang' => 'ru_RU',
                    'format' => 'json',
                    'geocode' => $model->$attribute,
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

            if (is_array($responseData) && ArrayHelper::getValue($responseData, $responseKeyPosition)) {

                $components = ArrayHelper::getValue($responseData, $responseKeyComponents);
                foreach ($components as $component) {
                    if ($component['kind'] === 'locality') {
                        $city = $component['name'];
                    }
                }

                if (City::findOne(Yii::$app->user->identity->city_id)->name === $city) {
                    $result = true;
                    $coords = ArrayHelper::getValue($responseData, $responseKeyPosition);
                    $location = ArrayHelper::getValue($responseData, $responseKeyAddress);

                    $model->latitude = floatval(explode(' ', $coords)[0]);
                    $model->longitude = floatval(explode(' ', $coords)[1]);
                    $model->location = $location;
                    $model->city_id = Yii::$app->user->identity->city_id;
                } else {
                    $this->addError($model, $attribute, 'Укажите локацию в вашем городе');

                }

            } else {
                $this->addError($model, $attribute, 'Неизвестная локация');
            }

        } catch (RequestException $e) {
        } catch (GuzzleException $e) {
            $this->addError($model, $attribute, $e->getMessage());

        }

        return $result;
    }
}
