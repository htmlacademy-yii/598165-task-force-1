<?php


namespace frontend\assets;


class DropzoneAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'https://rawgit.com/enyo/dropzone/master/dist/dropzone.css',
    ];
}
