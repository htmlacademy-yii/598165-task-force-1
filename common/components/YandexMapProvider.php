<?php

namespace common\components;

use yii\base\Component;

class YandexMapProvider extends Component implements MapProvider
{
    public string $url;
    public array $query;

    public string $apiKey;

    public function init()
    {
        parent::init();
        $this->query['apikey'] = $this->apiKey;
    }

    public function getUrl(): string
    {
        return $this->url  . '?' . http_build_query($this->query);
    }

}
