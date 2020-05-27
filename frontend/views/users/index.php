<?php

/* @var $this yii\web\View
 * @var \common\models\User[] $users
 * @var  \frontend\models\UsersFilter $usersFilter
 * @var \frontend\models\UsersSorting $usersSorting
 */


use frontend\models\Skill;
use frontend\widgets\StarRatingWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'TaskForce - Users';
?>

<section class="user__search">

    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <?php foreach (array_keys($usersSorting::SORTS) as $sortItem) : ?>
                <?php
                $currentClass = $sortItem === $usersSorting->currentSort ? ' user__search-item--current' : '';
                ?>
                <li class="user__search-item <?= $currentClass ?>">
                    <?= Html::a(
                        $usersSorting::SORTS[$sortItem],
                        [
                            '/users',
                            'sort' => $sortItem
                        ],
                        ['class' => 'link-regular']) ?>
                </li>

            <?php endforeach; ?>
        </ul>
    </div>
    <?php foreach ($users as $user) : ?>
        <div class="content-view__feedback-card user__search-wrapper">
            <div class="feedback-card__top">
                <div class="user__search-icon">
                    <a href="#">
                        <img src="<?= $user->avatar ?>" width="65" height="65" alt="user avatar">
                    </a>
                    <span><?= count($user->contractorTasks) ?> заданий</span>
                    <span><?= count($user->reviews) ?> отзывов</span>
                </div>
                <div class="feedback-card__top--name user__search-card">
                    <p class="link-name">
                        <a href="#" class="link-regular"><?= $user->name ?></a>
                    </p>

                    <?= StarRatingWidget::widget(['rating' => $user->rating]) ?>

                    <p class="user__search-content">
                        <?= $user->about ?>
                    </p>
                </div>
                <span class="new-task__time"><?= $user->last_seen_at ?></span>
            </div>
            <div class="link-specialization user__search-link--bottom">

                <?php foreach ($user->skills as $skill) : ?>
                    <a href="#" class="link-regular"><?= $skill->name ?></a>
                <?php endforeach ?>

            </div>
        </div>

    <?php endforeach ?>
</section>
<section class="search-task">

    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin(['options' => ['class' => 'search-task__form'],]); ?>
        <fieldset class="search-task__categories">
            <legend>Категории</legend>
            <?= Html::activeCheckboxList($usersFilter, 'skills',
                Skill::getFormFields(),
                [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $checkbox = Html::checkbox($name, $checked,
                            [
                                'id' => $value,
                                'value' => $value,
                                'class' => 'visually-hidden checkbox__input',
                            ]);
                        $label = Html::label($label, $value);
                        return $checkbox . $label;
                    }
                ]
            ); ?>
        </fieldset>

        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>
            <?= Html::activeCheckboxList($usersFilter, 'additional',
                $usersFilter->getAdditionalFields(),
                [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $checkbox = Html::checkbox($name, $checked,
                            [
                                'id' => $value,
                                'value' => $value,
                                'class' => 'visually-hidden checkbox__input',
                            ]);
                        $label = Html::label($label, $value);
                        return $checkbox . $label;
                    }
                ]) ?>
        </fieldset>
        <?= Html::activeLabel($usersFilter, 'search',
            [
                'class' => 'search-task__name',
                'label' => 'Поиск по имени'
            ]); ?>
        <?= Html::activeInput('search', $usersFilter, 'search',
            [
                'class' => 'input-middle input',
            ]) ?>
        <button class="button" type="submit">Искать</button>
        <?php ActiveForm::end() ?>
    </div>
</section>

<!--<form class="search-task__form" name="users" method="post" action="#">-->
<!--    <fieldset class="search-task__categories">-->
<!--        <legend>Категории</legend>-->
<!--        <input class="visually-hidden checkbox__input" id="101" type="checkbox" name="" value="" checked-->
<!--               disabled>-->
<!--        <label for="101">Курьерские услуги </label>-->
<!--        <input class="visually-hidden checkbox__input" id="102" type="checkbox" name="" value="" checked>-->
<!--        <label for="102">Грузоперевозки </label>-->
<!--        <input class="visually-hidden checkbox__input" id="103" type="checkbox" name="" value="">-->
<!--        <label for="103">Переводы </label>-->
<!--        <input class="visually-hidden checkbox__input" id="104" type="checkbox" name="" value="">-->
<!--        <label for="104">Строительство и ремонт </label>-->
<!--        <input class="visually-hidden checkbox__input" id="105" type="checkbox" name="" value="">-->
<!--        <label for="105">Выгул животных </label>-->
<!--    </fieldset>-->
<!--    <fieldset class="search-task__categories">-->
<!--        <legend>Дополнительно</legend>-->
<!--        <input class="visually-hidden checkbox__input" id="106" type="checkbox" name="" value="" disabled>-->
<!--        <label for="106">Сейчас свободен</label>-->
<!--        <input class="visually-hidden checkbox__input" id="107" type="checkbox" name="" value="" checked>-->
<!--        <label for="107">Сейчас онлайн</label>-->
<!--        <input class="visually-hidden checkbox__input" id="108" type="checkbox" name="" value="" checked>-->
<!--        <label for="108">Есть отзывы</label>-->
<!--        <input class="visually-hidden checkbox__input" id="109" type="checkbox" name="" value="" checked>-->
<!--        <label for="109">В избранном</label>-->
<!--    </fieldset>-->
<!--    <label class="search-task__name" for="110">Поиск по имени</label>-->
<!--    <input class="input-middle input" id="110" type="search" name="q" placeholder="">-->
<!--    <button class="button" type="submit">Искать</button>-->
<!--</form>-->

