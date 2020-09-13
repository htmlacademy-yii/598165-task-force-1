<?php


namespace frontend\assets;


class MapApiAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];
    public $js = [];

    public $jsOptions =
        [
            'position' => \yii\web\View::POS_HEAD,
        ];


    public function __construct($config = [])
    {
        $this->js[] = \Yii::$app->mapProvider->getUrl() . '?' . http_build_query(\Yii::$app->mapProvider->getQuery());

        parent::__construct($config);
    }


}
