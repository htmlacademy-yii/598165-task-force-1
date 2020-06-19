<?php


namespace frontend\widgets;


use yii\base\Widget;

class Age extends Widget
{
    public string $birthday = '';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();

        $birthday = new \DateTime($this->birthday);
        $now = new \DateTime();
        $age = $now->diff($birthday);
        return $age->y;

    }

}
