<?php


namespace frontend\widgets;


use frontend\models\User;
use yii\base\Widget;
use yii\helpers\Html;

class AvatarWidget extends Widget
{
    private const DEFAULT_WIDTH = 65;
    private const DEFAULT_HEIGHT = 65;

    public ?int $user_id = null;
    public int $width = self::DEFAULT_WIDTH;
    public int $height = self::DEFAULT_HEIGHT;

    public function run()
    {
        parent::run();

        $user =  User::findOne($this->user_id);

        if (isset($user) && isset($user->avatar)) {
            return Html::img($user->avatar, [
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
