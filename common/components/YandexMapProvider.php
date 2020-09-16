<?php

namespace common\components;

use yii\base\Component;

class YandexMapProvider extends Component implements MapProvider
{
    public string $url;
    public string $apiKey;
    public string $lang;

    public function getUrl(): string
    {
        return $this->url  . '?' . http_build_query([
                'apikey' => $this->apiKey,
                'lang' => $this->lang,
            ]);
    }

}
