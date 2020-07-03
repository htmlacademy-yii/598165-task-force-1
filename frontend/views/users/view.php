<?php
/* @var $this yii\web\View
 * @var \frontend\models\User $user
 * @var City[] $cities;
 */

use frontend\models\City;
use frontend\widgets\AvatarWidget;
use frontend\widgets\StarRatingWidget;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'TaskForce - Profile';
$this->params['cities'] = $cities;

?>
<section class="content-view">
    <div class="user__card-wrapper">

        <div class="user__card">
            <?= AvatarWidget::widget(['user' => $user, 'width' => 120, 'height' => 120]); ?>

            <div class="content-view__headline">
                <h1><?= $user->name ?></h1>
                <p>Россия,<?= '&ensp;' . City::findOne($user->city_id)->name; ?><?= $user->age ? ', ' . $user->age: '' ; ?></p>
                <div class="profile-mini__name five-stars__rate">
                    <?= StarRatingWidget::widget(['rating' => $user->rating]) ?>

                </div>
                <b class="done-task">Выполнил <?= count($user->contractorTasks) ?> заказов</b>
                <b class="done-review">Получил <?= count($user->reviews) ?>  отзывов</b>
            </div>
            <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                <span> <?= 'Был на сайте '
                    . \Yii::$app->formatter->asRelativeTime($user->last_seen_at); ?> </span>
                <a href="#"><b></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p><?= $user->about ?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <?php foreach ($user->skills as $skill) : ?>
                        <a href="#" class="link-regular"><?= $skill->name ?></a>
                    <?php endforeach; ?>
                </div>
                <h3 class="content-view__h3">Контакты</h3>
                <div class="user__card-link">
                    <a class="user__card-link--tel link-regular" href="#"><?= $user->phone ?></a>
                    <a class="user__card-link--email link-regular" href="#"><?= $user->email ?></a>
                    <a class="user__card-link--skype link-regular" href="#"><?= $user->skypeid ?></a>
                </div>
            </div>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <a href="#"><img src="/img/rome-photo.jpg" width="85" height="86" alt="Фото работы"></a>
                <a href="#"><img src="/img/smartphone-photo.png" width="85" height="86" alt="Фото работы"></a>
                <a href="#"><img src="/img/dotonbori-photo.png" width="85" height="86" alt="Фото работы"></a>
            </div>
        </div>
    </div>
    <?php if (count($user->reviews)) : ?>
        <div class="content-view__feedback">
            <h2>Отзывы<span>(<?= count($user->reviews); ?>)</span></h2>
            <div class="content-view__feedback-wrapper reviews-wrapper">
                <?php foreach ($user->reviews as $review) : ?>
                    <div class="feedback-card__reviews">
                        <p class="link-task link">Задание
                            <?= Html::a($review->task->title,
                                ['tasks/view', 'id' => $review->task->id],
                                ['class' => 'link-regular']); ?>
                        </p>
                        <div class="card__review">
                            <a href="<?= Url::to(['users/view', 'id' => $review->user->id]); ?>">
                                <?= AvatarWidget::widget(['user' => $user, 'width' => 55, 'height' => 55]); ?>
                            </a>
                            <div class="feedback-card__reviews-content">
                                <p class="link-name link">
                                    <?= Html::a($review->user->name,
                                        ['users/view', 'id' => $review->user->id],
                                        ['class' => 'link-regular']); ?>
                                </p>
                                <p class="review-text">
                                    <?= $review->description ?>
                                </p>
                            </div>
                            <div class="card__review-rate">
                                <?php $ratingClass = $review->rating <= 3 ? 'three-rate' : 'five-rate'; ?>
                                <p class="<?= $ratingClass . ' big-rate'?> ">
                                    <?= $review->rating ?>
                                    <span></span>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
<section class="connect-desk">
    <div class="connect-desk__chat">
        <h3>Переписка</h3>
        <div class="chat__overflow">
            <div class="chat__message chat__message--out">
                <p class="chat__message-time">10.05.2019, 14:56</p>
                <p class="chat__message-text">Привет. Во сколько сможешь
                    приступить к работе?</p>
            </div>
            <div class="chat__message chat__message--in">
                <p class="chat__message-time">10.05.2019, 14:57</p>
                <p class="chat__message-text">На задание
                    выделены всего сутки, так что через час</p>
            </div>
            <div class="chat__message chat__message--out">
                <p class="chat__message-time">10.05.2019, 14:57</p>
                <p class="chat__message-text">Хорошо. Думаю, мы справимся</p>
            </div>
        </div>
        <p class="chat__your-message">Ваше сообщение</p>
        <form class="chat__form">
            <textarea class="input textarea textarea-chat" rows="2" name="message-text" placeholder="Текст сообщения"></textarea>
            <button class="button chat__button" type="submit">Отправить</button>
        </form>
    </div>
</section>
