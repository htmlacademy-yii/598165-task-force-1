<?php


namespace frontend\widgets;


use yii\base\Widget;

class MapWidget extends Widget
{
    const DEFAULT_WIDTH = '300px';
    const DEFAULT_HEIGHT = '150px';
    const DEFAULT_ZOOM = 10;

    public float $longitude;
    public float $latitude;
    public string $width = self::DEFAULT_WIDTH;
    public string $height = self::DEFAULT_HEIGHT;
    public int $zoom = self::DEFAULT_ZOOM;


 public function run()
 {
     return $this->render('map', [
         'longitude' => $this->longitude,
         'latitude' => $this->latitude,
         'width' => $this->width,
         'height' => $this->height,
         'zoom' => $this->zoom,
     ]);
 }
}
