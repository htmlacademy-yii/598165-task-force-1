<?php


namespace frontend\widgets;


use yii\base\Widget;

class RelativeTime extends Widget
{
    public string $from = '';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();
        return $this->calculateRelativeTime($this->from);

    }

    private function calculateRelativeTime($time) : string {
        $timestamp = strtotime($time);

        $timeDifference = time() - $timestamp;
        $dayDifference = floor($timeDifference / 86400);

        if($dayDifference == 0)
        {
            if($timeDifference < 60) return 'только что';
            if($timeDifference < 120) return 'одну минуту назад';
            if($timeDifference < 3600) return floor($timeDifference / 60) . ' минут назад';
            if($timeDifference < 7200) return 'час назад';
            if($timeDifference < 86400) return floor($timeDifference / 3600) . ' часов назад';
        }
        if($dayDifference == 1) return 'Вчера';
        if($dayDifference < 30) return $dayDifference . ' дней назад';

        return ceil($dayDifference / 30) . ' месяцев назад';
    }

}
