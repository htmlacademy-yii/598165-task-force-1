<?php


namespace frontend\models\forms;


use frontend\models\File;
use frontend\models\Skill;
use frontend\models\Task;
use TaskForce\models\TaskStatus;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * This is the model class for form "Create Task".
 *
 * @property string $title;
 * @property string $description;
 * @property string $skill;
 * @property string[] $files;
 * @property string $budget;
 * @property string $dueDate;
 */
class CreateTaskForm extends Model
{
    public string $title = '';
    public string $description = '';
    public string $skill = '';
    public array $files = [];
    public string $budget = '';
    public string $dueDate = '';

    public Task $newTask;


    public function rules()
    {
        return [


            ['title', 'required', 'message' => 'Это поле должно быть заполнено'],
            ['title', 'trim'],
            [
                'title',
                'string',
                'min' => 10,
                'tooShort' => 'Это поле должно содержать не менее 10 символов'
            ],

            ['description', 'required', 'message' => 'Это поле должно быть заполнено'],
            ['description', 'trim'],
            [
                'description',
                'string',
                'min' => 30,
                'tooShort' => 'Это поле должно содержать не менее 30 символов'
            ],

            ['skill', 'required'],
            [
                'skill',
                'exist',
                'targetClass' => Skill::class,
                'targetAttribute' => 'id',
                'message' => 'Задание должно принадлежать одной из категорий'
            ],

            ['files', 'safe'],

            ['budget', 'trim'],
            [
                'budget',
                'integer',
                'min' => 0,
                'tooSmall' => 'Бюджет не может быть меньше нуля'
            ],

            ['dueDate', 'date', 'format' => 'yyyy-mm-dd'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Мне нужно',
            'description' => 'Подробности задания',
            'skill' => 'Категория',
            'files' => 'Файлы',
            'budget' => 'Бюджет',
            'dueDate' => 'Срок исполнения',
        ];
    }


    /**
     * Create new task.
     *
     * @return bool whether the creating new task was successful
     */
    public function createTask(): bool
    {

        if (!$this->validate()) {
            return false;
        }

        $this->newTask = new Task();
        $this->newTask->status = TaskStatus::NEW;
        $this->newTask->title = $this->title;
        $this->newTask->description = $this->description;
        $this->newTask->budget = $this->budget ? intval($this->budget) : null;
        $this->newTask->due_date_at = $this->dueDate ? $this->dueDate : null;
        $this->newTask->skill_id = intval($this->skill);
        $this->newTask->client_id = Yii::$app->user->getId();

        if (!$this->newTask->save()) {
            return false;
        }

        $this->files = UploadedFile::getInstances($this, 'files');

        foreach ($this->files as $file) {
            $transaction = File::getDb()->beginTransaction();
            try {
                $file->saveAs('@app/uploads/' . $file->baseName . '.' . $file->extension);

                $newFile = new File();
                $newFile->name = $file->name;
                $newFile->src = 'uploads/';
                $newFile->task_id = $this->newTask->id;
                $newFile->save();
                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return true;
    }
}

