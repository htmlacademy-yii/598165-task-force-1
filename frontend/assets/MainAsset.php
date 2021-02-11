<?php


namespace frontend\assets;


class MainAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/autocomplete.css',
        'css/dropzone.css',
    ];
    public $js = [
        'js/main.js',
        'js/autocomplete.js',
        'js/dropzone.js',
        'js/lightbulb.js',
        'js/map.js',
        'js/messenger.js',
        'js/settings.js'
    ];
    public $depends = [
        'frontend\assets\AppAsset'
    ];


}
