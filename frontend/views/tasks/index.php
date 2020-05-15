<?php

/* @var $this yii\web\View
 * @var \common\models\Task[] $tasks
 * @var $skills
 * @var $filter
 */

use yii\helpers\Html;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

$this->title = 'TaskForce - Tasks';
?>


<section class="new-task">

    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php foreach ($tasks as $task): ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <a href="#" class="link-regular">
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
                <span class="new-task__time"><?= $task->created_at ?></span>
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
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'search-task__form'],
        ]); ?>
        <fieldset class="search-task__categories">
            <legend>Категории</legend>

            <?= html::activeCheckboxList($filter, 'skills', $skills, [
                'item' => function ($index, $label, $name, $checked, $value) {
                    return
                        '<input class="visually-hidden checkbox__input" id="' . ($value + 1) .
                        '" type="checkbox" name="' . $name .
                        '" value="' . ($value + 1) . '">
                        <label for="' . ($value + 1) . '">' . $label . '</label>';
                }
            ]); ?>
        </fieldset>

        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>

            <?= html::activeCheckboxList($filter, 'additional',
                ['withoutResponses' => 'Без откликов', 'remoteWork' => 'Удаленная работа'],
                [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        return
                            '<input class="visually-hidden checkbox__input" id="' . $value .
                            '" type="checkbox" name="' . $name .
                            '" value="' . $value . '"' .
                            ($checked ? " checked" : "") . '>
                        <label for="' . $value . '">' . $label . '</label>';
                    }
                ]); ?>
        </fieldset>

        <label class="search-task__name" for="8">Период</label>
        <?php echo html::activeDropDownList($filter, 'time', [
            "day" => 'За день',
            "week" => 'За неделю',
            "month" => 'За месяц',
        ], ['class' => 'multiple-select input']); ?>

        <label class="search-task__name" for="9">Поиск по названию</label>
        <?= html::activeInput('search', $filter, 'search', ['class' => 'input-middle input']) ?>




        <button class="button" type="submit">Искать</button>

        <?php ActiveForm::end(); ?>
        </fieldset>

    </div>
</section>

<!--<section class="search-task">-->
<!--    <div class="search-task__wrapper">-->
<!--        <form class="search-task__form" name="test" method="post" action="#">-->
<!--            <fieldset class="search-task__categories">-->
<!--                <legend>Категории</legend>-->
<!--                <input class="visually-hidden checkbox__input" id="1" type="checkbox" name="" value="" checked>-->
<!--                <label for="1">Курьерские услуги </label>-->
<!--                <input class="visually-hidden checkbox__input" id="2" type="checkbox" name="" value="" checked>-->
<!--                <label for="2">Грузоперевозки </label>-->
<!--                <input class="visually-hidden checkbox__input" id="3" type="checkbox" name="" value="">-->
<!--                <label for="3">Переводы </label>-->
<!--                <input class="visually-hidden checkbox__input" id="4" type="checkbox" name="" value="">-->
<!--                <label for="4">Строительство и ремонт </label>-->
<!--                <input class="visually-hidden checkbox__input" id="5" type="checkbox" name="" value="">-->
<!--                <label for="5">Выгул животных </label>-->
<!--            </fieldset>-->
<!--            <fieldset class="search-task__categories">-->
<!--                <legend>Дополнительно</legend>-->
<!--                <input class="visually-hidden checkbox__input" id="6" type="checkbox" name="" value="">-->
<!--                <label for="6">Без откликов</label>-->
<!--                <input class="visually-hidden checkbox__input" id="7" type="checkbox" name="" value="" checked>-->
<!--                <label for="7">Удаленная работа </label>-->
<!--            </fieldset>-->
<!--            <label class="search-task__name" for="8">Период</label>-->
<!--            <select class="multiple-select input" id="8" size="1" name="time[]">-->
<!--                <option value="day">За день</option>-->
<!--                <option selected value="week">За неделю</option>-->
<!--                <option value="month">За месяц</option>-->
<!--            </select>-->
<!--            <label class="search-task__name" for="9">Поиск по названию</label>-->
<!--            <input class="input-middle input" id="9" type="search" name="q" placeholder="">-->
<!--            <button class="button" type="submit">Искать</button>-->
<!--        </form>-->
<!---->
<!--    </div>-->
<!--</section>-->
