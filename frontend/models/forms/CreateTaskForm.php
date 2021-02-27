<?php


namespace frontend\models\forms;


use frontend\models\TaskHasFiles;
use frontend\models\File;
use frontend\models\Skill;
use frontend\models\Task;
use frontend\validators\LocationValidator;
use TaskForce\models\TaskStatus;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\FileHelper;
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
 * @property string $location;
 * @property float $latitude;
 * @property float $longitude;
 * @property int $city_id;
 */
class CreateTaskForm extends Model
{
    public string $title = '';
    public string $description = '';
    public string $skill = '';
    public array $files = [];
    public string $budget = '';
    public string $dueDate = '';
    public string $location = '';
    public string  $latitude = '';
    public string $longitude = '';
    public string $city_id = '';

    public Task $newTask;


    public function rules(): array
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

            [['files','latitude', 'longitude', 'city_id'], 'safe'],

            ['budget', 'trim'],
            [
                'budget',
                'integer',
                'min' => 0,
                'tooSmall' => 'Бюджет не может быть меньше нуля'
            ],

            ['dueDate', 'date', 'format' => 'yyyy-mm-dd'],
            ['location', 'trim'],
            ['location', LocationValidator::class],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'Мне нужно',
            'description' => 'Подробности задания',
            'skill' => 'Категория',
            'files' => 'Файлы',
            'budget' => 'Бюджет',
            'dueDate' => 'Срок исполнения',
            'location' => 'Локация',
        ];
    }


    /**
     * Create new task.
     *
     * @return bool whether the creating new task was successful
     * @throws Throwable
     * @throws Exception
     * @throws \yii\db\Exception
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
        $this->newTask->address = $this->location;
        $this->newTask->latitude = $this->latitude ? floatval($this->latitude) : null;
        $this->newTask->longitude = $this->longitude ? floatval($this->longitude) : null;
        $this->newTask->city_id = $this->city_id ? intval($this->city_id) : null;


        if (!$this->newTask->save()) {
            return false;
        }

        $this->files = UploadedFile::getInstances($this, 'files');
        $src = Yii::getAlias('@webroot/uploads/task') . $this->newTask->id;

        foreach ($this->files as $file) {
            $transaction = File::getDb()->beginTransaction();
            try {
                FileHelper::createDirectory($src);
                $file->saveAs($src . '/' . $file->name);

                $newFile = new File();
                $newFile->name = $file->name;
                $newFile->src = 'uploads/task' . $this->newTask->id;
                $newFile->save();

                $relation = new TaskHasFiles();
                $relation->task_id = $this->newTask->id;
                $relation->file_id = $newFile->id;
                $relation->save();

                $transaction->commit();
            } catch (Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return true;
    }
}

