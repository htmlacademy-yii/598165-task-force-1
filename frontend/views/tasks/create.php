<?php
/**
 * @var CreateTaskForm $createTaskForm
 */


use frontend\assets\AutocompleteAsset;
use frontend\assets\CreateTaskAsset;
use frontend\models\forms\CreateTaskForm;
use frontend\models\Skill;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

//AutocompleteAsset::register($this);
CreateTaskAsset::register($this);
?>
<section class="create__task">

    <h1>Публикация нового задания</h1>
    <div class="create__task-main">
        <?php $form = ActiveForm::begin([
            'options' => [
                'id' => 'task-form',
                'class' => 'create__task-form form-create',
            ],
            'fieldConfig' => [
                'hintOptions' => ['tag' => 'span']
            ]
        ]) ?>

        <?= $form->field($createTaskForm, 'title')
            ->textarea([
                'class' => 'input textarea',
                'rows' => 1,
                'placeholder' => 'Повесить полку',
                'spellcheck' => 'false'
            ])
            ->hint('Кратко опишите суть работы');
        ?>

        <?= $form->field($createTaskForm, 'description')
            ->textarea([
                'class' => 'input textarea',
                'rows' => 7,
                'placeholder' => 'Place your text',
                'spellcheck' => 'false'
            ])
            ->hint('Укажите все пожелания и детали,
            чтобы исполнителям было проще соориентироваться');
        ?>

        <?= $form->field($createTaskForm, 'skill')
            ->dropDownList(ArrayHelper::map(Skill::find()->asArray()->all(), 'id', 'name'), [
                'class' => 'multiple-select input multiple-select-big',
                'size' => 1,
            ])
            ->hint('Выберите категорию');
        ?>


        <label>Файлы</label>
        <span>Загрузите файлы, которые помогут исполнителю
            лучше выполнить или оценить работу</span>

        <?= $form->field($createTaskForm, 'files[]', [
            'template' => "{input}\n{label}"
        ])
            ->fileInput([
                'multiple' => true,
                'class' => 'dropzone'

            ])
            ->label('Добавить новый файл', ['class' => false])

        ?>

        <?= $form->field($createTaskForm, 'location')
            ->input('search', [
                'class' => 'input-navigation input-middle input',
                'id' => "autoComplete",
                'placeholder' => 'Санкт-Петербург, Калининский район',
                'autocomplete' => 'off',
            ])
            ->hint('Укажите адрес исполнения, если задание требует присутствия'); ?>

        <div class="create__price-time">
            <div class="create__price-time--wrapper">
                <?= $form->field($createTaskForm, 'budget')
                    ->textarea([
                        'class' => 'input textarea input-money ',
                        'rows' => 1,
                        'placeholder' => '1000'
                    ])
                    ->hint('Не заполняйте для оценки исполнителем');
                ?>
            </div>

            <div class="create__price-time--wrapper">
                <?= $form->field($createTaskForm, 'dueDate')
                    ->input('date', [
                        'class' => 'input textarea input-date ',
                        'rows' => 1,
                        'placeholder'=>'10.11.2020'
                    ])
                    ->hint('Укажите крайний срок исполнения');
                ?>
            </div>
        </div>

        <?php ActiveForm::end() ?>

        <div class="create__warnings">
            <div class="warning-item warning-item--advice">
                <h2>Правила хорошего описания</h2>
                <h3>Подробности</h3>
                <p>Друзья, не используйте случайный<br>
                    контент – ни наш, ни чей-либо еще. Заполняйте свои
                    макеты, вайрфреймы, мокапы и прототипы реальным
                    содержимым.</p>
                <h3>Файлы</h3>
                <p>Если загружаете фотографии объекта, то убедитесь,
                    что всё в фокусе, а фото показывает объект со всех
                    ракурсов.</p>
            </div>

            <?php if (count($createTaskForm->errors)) : ?>
                <div class="warning-item warning-item--error">
                    <h2>Ошибки заполнения формы</h2>
                    <?php foreach ($createTaskForm->errors as $attribute => $errors) : ?>

                        <h3><?= $createTaskForm->getAttributeLabel($attribute) ?></h3>
                        <p>
                        <?php foreach ($errors as $error) : ?>
                            <?= $error ?><br>
                        <?php endforeach; ?>
                        </p>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?= Html::submitButton('Опубликовать', [
        'form' => 'task-form',
        'class' => 'button'
    ]) ?>
</section>
