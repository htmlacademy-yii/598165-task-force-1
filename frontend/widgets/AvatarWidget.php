<?php


namespace frontend\widgets;


use frontend\models\User;
use yii\base\Widget;
use yii\helpers\Html;

class AvatarWidget extends Widget
{
    public ?User $user = null;
    public int $width = 65;
    public int $height = 65;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();

        if (isset($this->user->avatar)) {
            return Html::img($this->user->avatar, [
                'width' => $this->width,
                'height' => $this->height,
                'alt' => 'user avatar'
            ]);
        }

        return Html::tag('svg', '<use xlink:href="#default-avatar"></use>', [
            'width' => $this->width,
            'height' => $this->height,
        ]);
    }

}
