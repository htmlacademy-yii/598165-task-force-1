<?php

namespace frontend\widgets;

use yii\base\Widget;

class StarRatingWidget extends Widget
{
    public float $rating = 0;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();
        $stars = round($this->rating);
        $html = '';

        foreach (range(1, 5) as $star) {
            if ($star <= $stars) {
                $html .= '<span></span>';
            } else {
                $html .= '<span class="star-disabled"></span>';
            }
        }
        $html .= '<b>' . round($this->rating, 2) . '</b>';
        return $html;
    }

}
