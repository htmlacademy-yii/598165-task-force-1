<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Landing frontend application asset bundle.
 */
class LandingAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/landing.css',
    ];
    public $js = [
        'js/landing.js'
    ];
    public $depends = [
        'frontend\assets\AppAsset'
    ];
}
