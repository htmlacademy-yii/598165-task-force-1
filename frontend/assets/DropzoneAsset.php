<?php


namespace frontend\assets;


use yii\web\AssetBundle;

class DropzoneAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'https://rawgit.com/enyo/dropzone/master/dist/dropzone.css',
    ];
}
