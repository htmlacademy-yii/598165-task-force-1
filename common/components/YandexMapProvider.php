<?php

namespace common\components;

use yii\base\Component;
use yii\web\ServerErrorHttpException;

class YandexMapProvider extends Component implements MapProvider
{
    public string $url;
    public ?string $apiKey;
    public string $lang;

    public function __construct($config=[])
    {
        parent::__construct($config);

        if (!isset($this->apiKey)) {
            throw new ServerErrorHttpException('Map provider api key is not set');
        }
    }

    public function getUrl(): string
    {
        return $this->url  . '?' . http_build_query([
                'apikey' => $this->apiKey,
                'lang' => $this->lang,
            ]);
    }

}
