<?php


namespace frontend\widgets;


use frontend\models\User;
use yii\base\Widget;
use yii\helpers\Html;

class AvatarWidget extends Widget
{
    private const DEFAULT_WIDTH = 65;
    private const DEFAULT_HEIGHT = 65;

    public ?User $user = null;
    public int $width = self::DEFAULT_WIDTH;
    public int $height = self::DEFAULT_HEIGHT;

    public function run()
    {
        parent::run();

        if (isset($this->user) && isset($this->user->avatar)) {
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
