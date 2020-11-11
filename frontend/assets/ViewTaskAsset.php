<?php


namespace frontend\assets;


use yii\web\AssetBundle;

class ViewTaskAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];
    public $js = [
        'js/messenger.js',
    ];

}
