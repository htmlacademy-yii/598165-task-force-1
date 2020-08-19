<?php


namespace frontend\models\forms;


use frontend\models\Response;
use frontend\models\Task;
use yii\base\Model;

class ResponseTaskForm extends Model
{
    public string $payment = '';
    public string $comment = '';

    private Task $task;

    public function __construct($task)
    {
        parent::__construct();
        $this->task = $task;
    }

    public function rules()
    {
        return [
            ['payment', 'integer'],
            ['payment', 'compare', 'compareValue' => 0, 'operator' => '>'],
            ['comment', 'trim']
        ];
    }

    public function attributeLabels()
    {
        return [
            'payment' => 'Ваша цена',
            'comment' => 'Комментарий'
        ];
    }

    /**
     * Create new response.
     *
     * @return bool whether the creating new response was successful
     */
    public function createResponse() {
        if (!$this->validate()) {
            return false;
        }

        $newResponse = new Response();
        $newResponse->user_id = \Yii::$app->user->id;
        $newResponse->task_id = $this->task->id;
        $newResponse->description = $this->comment;
        $newResponse->budget = intval($this->payment);
        $newResponse->save();
        return true;
    }
}
