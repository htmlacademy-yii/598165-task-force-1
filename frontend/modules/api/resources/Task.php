<?php


namespace frontend\modules\api\resources;


use frontend\models\User;

class Task extends \frontend\models\Task
{
    public function fields()
    {
        return [
            'title',
            'published_at' => 'created_at',
            'new_messages' => function () {
                return count($this->messages);
            },
            'author_name' => function () {
                return User::findOne($this->client_id)->name;
            },
            "id"
        ];
    }

}

