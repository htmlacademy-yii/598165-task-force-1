<?php

namespace common\components;

use yii\base\Component;

class ServiceProvider extends Component
{
    public string $url;
    public array $query;
    public string $apiKeyName;
    public string $addressKeyName;

    public function init()
    {
        parent::init();
        $this->query[$this->apiKeyName] = \Yii::$app->params['mapApiKey'];

    }

    public function getUrl() {
        return $this->url;
    }

    public function getQuery() {
        return $this->query;
    }

}
