<?php
/* @var float $latitude
 * @var float $longitude
 * @var string $width
 * @var string $height
 * @var int $zoom
 */


use frontend\assets\MapAsset;
use yii\helpers\Html;
MapAsset::register($this);


echo Html::tag('div', '', [
    'class' => 'content-view__map',
    'id' => 'map',
    'style' => "width: $width; height: $height",
    'data-longitude' => $longitude,
    'data-latitude' => $latitude,
    'data-zoom' => $zoom,
]);

