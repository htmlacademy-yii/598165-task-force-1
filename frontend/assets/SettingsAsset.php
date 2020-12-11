<?php


namespace frontend\assets;


class SettingsAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = ['https://rawgit.com/enyo/dropzone/master/dist/dropzone.css', 'css/dropzone.css'];
    public $js = [
        'js/dropzone.js',
        'js/settings.js',
    ];

}
