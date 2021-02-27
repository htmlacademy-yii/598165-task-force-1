<?php


namespace frontend\models\forms;


use yii\base\Model;

class FinishTaskForm extends Model
{
    public ?string $completion =  null;
    public ?string $comment = null;
    public ?int $rating = null;

    const COMPLETION_YES = 'yes';
    const COMPLETION_PROBLEMS = 'difficult';

    const COMPLETION = [
        self::COMPLETION_YES => 'да',
        self::COMPLETION_PROBLEMS => 'Возникли проблемы'
    ];

    public function rules(): array
    {
        return [
            ['completion', 'required', 'skipOnEmpty' => false],
            [
                'completion',
                'in',
                'range' => array_keys(self::COMPLETION),
            ],
            [['comment'], 'trim'],
            ['rating', 'required'],
            [['rating'], 'integer', 'min' => 1, 'max' => 5]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'completion' => 'Задание выполнено?',
            'comment' => 'Комментарий',
            'rating' => 'Оценка'
        ];
    }

    /**
     * Returns the list with completion values.
     * @return string[]
     */
    public static function getCompletionFields() : array
    {
        return self::COMPLETION;
    }

}
