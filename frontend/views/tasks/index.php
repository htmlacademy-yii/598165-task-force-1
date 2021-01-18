<?php

/* @var $this yii\web\View
 * @var Task[] $tasks
 * @var $taskFilter
 * @var $pages
 */

use frontend\models\Skill;
use frontend\models\forms\TasksFilter;
use frontend\models\Task;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

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
                <div class="new-task__icon new-task__icon--<?= $task->skill->icon ?>"></div>
                <p class="new-task_description">
                    <?= $task->description ?>
                </p>
                <?php if ($task->budget) : ?>
                    <b class="new-task__price new-task__price--translation">
                        <?= $task->budget ?><b> ₽</b>
                    </b>
                <?php endif; ?>
                <p class="new-task__place"><?= isset($task->city) ? $task->city->name : "" ?></p>

                <span class="new-task__time">
                    <?= \Yii::$app->formatter->asRelativeTime($task->created_at) ?>
                </span>

            </div>
        <?php endforeach ?>
        <div class="new-task__pagination">

            <?php
                echo LinkPager::widget([
                    'pagination' => $pages,
                    'options' => ['class' => 'new-task__pagination-list'],
                    'pageCssClass' => 'pagination__item',
                    'activePageCssClass' => 'pagination__item--current',
                    'prevPageCssClass' => 'pagination__item',
                    'nextPageCssClass' => 'pagination__item',
                    'prevPageLabel' => '',
                    'nextPageLabel' => '',
                ]);
            ?>
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
                        $checkbox = Html::checkbox("TasksFilter[additional][{$index}]", $checked,
                            [
                                'id' => $value,
                                'value' => $value,
                                'class' => "visually-hidden checkbox__input",
                                'uncheck' => 'unchecked',
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
