<?php

/* @var $this yii\web\View
 * @var \frontend\models\Task[] $tasks
 * @var $taskFilter
 * @var $citySelect \frontend\models\CitySelect
 */

use frontend\models\Skill;
use frontend\models\forms\TasksFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<section class="new-task">

    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php foreach ($tasks as $task): ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <a href="<?= Url::to(['tasks/view', 'id' => $task->id]); ?>"
                       class="link-regular">
                        <h2><?= $task->title ?></h2>
                    </a>
                    <a class="new-task__type link-regular" href="#"><p><?= $task->skill->name ?></p></a>
                </div>
                <div class="new-task__icon new-task__icon--translation"></div>
                <p class="new-task_description">
                    <?= $task->description ?>
                </p>
                <b class="new-task__price new-task__price--translation"><?= $task->budget ?><b> ₽</b></b>
                <p class="new-task__place"><?= isset($task->city) ? $task->city->name : "" ?></p>

                <span class="new-task__time">
                    <?= \Yii::$app->formatter->asRelativeTime($task->created_at) ?>
                </span>

            </div>
        <?php endforeach ?>
        <div class="new-task__pagination">
            <ul class="new-task__pagination-list">
                <li class="pagination__item"><a href="#"></a></li>
                <li class="pagination__item pagination__item--current">
                    <a>1</a></li>
                <li class="pagination__item"><a href="#">2</a></li>
                <li class="pagination__item"><a href="#">3</a></li>
                <li class="pagination__item"><a href="#"></a></li>
            </ul>
        </div>
</section>

<section class="search-task">
    <div class="search-task__wrapper">

        <?php $form = ActiveForm::begin(['options' => ['class' => 'search-task__form']]); ?>

        <fieldset class="search-task__categories">
            <legend>Категории</legend>

            <?= Html::activeCheckboxList($taskFilter, 'skills',
                Skill::getFormFields(),
                [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $checkbox = Html::checkbox($name, $checked,
                            [
                                'id' => $value,
                                'value' => $value,
                                'class' => "visually-hidden checkbox__input"
                            ]);
                        $label = Html::label($label, $value);
                        return $checkbox . $label;
                    },
                    'unselect' => null,
                ]); ?>

        </fieldset>

        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>

            <?= Html::activeCheckboxList($taskFilter, 'additional',
                TasksFilter::getAdditionalFields(),
                [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $checkbox = Html::checkbox($name, $checked,
                            [
                                'id' => $value,
                                'value' => $value,
                                'class' => "visually-hidden checkbox__input"
                            ]);
                        $label = Html::label($label, $value);
                        return $checkbox . $label;
                    },
                    'unselect' => null,
                ]); ?>
        </fieldset>

        <?= html::activeLabel($taskFilter, 'period',
            [
                'class' => 'search-task__name',
                'label' => 'Период',
            ]) ?>
        <?= html::activeDropDownList($taskFilter, 'period',
            TasksFilter::getPeriodsFields(),
            ['class' => 'multiple-select input']); ?>

        <?= html::activeLabel($taskFilter, 'search',
            [
                'class' => 'search-task__name',
                'label' => 'Поиск по названию',
            ]) ?>
        <?= html::activeInput('search', $taskFilter, 'search',
            ['class' => 'input-middle input']) ?>

        <button class="button" type="submit">Искать</button>

        <?php ActiveForm::end(); ?>
        </fieldset>

    </div>
</section>
