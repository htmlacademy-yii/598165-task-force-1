<?php
namespace frontend\api\modules\v1\resources;


class Message extends \frontend\models\Message
{
    public function fields()
    {
        return [
            'message' => 'text',
            'published_at' => function () {
                return \Yii::$app->formatter->asRelativeTime($this->created_at);
            },
            'is_mine' => function () {
                return \Yii::$app->user->getId() === $this->user_id;
            }
        ];
    }
}
