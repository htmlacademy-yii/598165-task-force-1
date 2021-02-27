<?php


namespace frontend\assets;


use yii\web\AssetBundle;

class AutocompleteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@7.2.0/dist/js/autoComplete.min.js',
    ];

}
