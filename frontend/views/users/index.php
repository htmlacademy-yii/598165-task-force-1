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
            <?php foreach ($usersSorting::SORTS as $sortType => $sortLabel) : ?>
                <?php
                $currentClass = $sortType === $usersSorting->getCurrentSort() ? ' user__search-item--current' : '';
                ?>
                <li class="user__search-item <?= $currentClass ?>">
                    <?= Html::a(
                        $sortLabel,
                        [
                            '/users',
                            'sort' => $sortType
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
