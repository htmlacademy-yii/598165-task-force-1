<?php


namespace frontend\assets;


use Yii;
use yii\web\AssetBundle;
use yii\web\View;

class MapApiAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];
    public $js = [];

    public $jsOptions =
        [
            'position' => View::POS_HEAD,
        ];


    public function __construct($config = [])
    {
        $this->js[] = Yii::$app->mapProvider->getUrl();

        parent::__construct($config);
    }


}
