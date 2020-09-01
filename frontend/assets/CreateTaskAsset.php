<?php


namespace frontend\assets;


use yii\web\AssetBundle;

class CreateTaskAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/autocomplete.css',
    ];
    public $js = [
        'https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@7.2.0/dist/js/autoComplete.min.js',
        'js/autocomplete.js',
    ];
}
