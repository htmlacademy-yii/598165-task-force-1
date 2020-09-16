<?php


namespace frontend\assets;


use yii\web\AssetBundle;

class MapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];
    public $js = [
        'js/map.js',
    ];
    public $depends = [
        'frontend\assets\MapApiAsset',
    ];
}
