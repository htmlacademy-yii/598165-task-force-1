<?php
/* @var $this yii\web\View
 * @var \common\models\User $user
 */

use frontend\widgets\StarRatingWidget;

$this->title = 'TaskForce - Profile';
?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="content-view">
            <div class="user__card-wrapper">



                <div class="user__card">
                    <img src="<?= $user->avatar ?>" width="120" height="120" alt="Аватар пользователя">
                    <div class="content-view__headline">
                        <h1><?= $user->name ?></h1>
                        <p>Россия, Санкт-Петербург, <?= $user->birthday_at?></p>
                        <div class="profile-mini__name five-stars__rate">
                            <?= StarRatingWidget::widget(['rating' => $user->rating]) ?>
                        </div>
                        <b class="done-task">Выполнил <?= count($user->contractorTasks) ?> заказов</b>
                        <b class="done-review">Получил <?= count($user->reviews) ?>  отзывов</b>
                    </div>
                    <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                        <span> <?= $user->last_seen_at?> </span>
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
                        <a href="#"><img src="./img/rome-photo.jpg" width="85" height="86" alt="Фото работы"></a>
                        <a href="#"><img src="./img/smartphone-photo.png" width="85" height="86" alt="Фото работы"></a>
                        <a href="#"><img src="./img/dotonbori-photo.png" width="85" height="86" alt="Фото работы"></a>
                    </div>
                </div>
            </div>
            <div class="content-view__feedback">
                <h2>Отзывы<span>(2)</span></h2>
                <div class="content-view__feedback-wrapper reviews-wrapper">
                    <?php foreach ($user->reviews as $review) : ?>
                        <div class="feedback-card__reviews">
                            <p class="link-task link">Задание
                                <a href="#" class="link-regular">
                                    <?= $review->task->title?>
                                </a>
                            </p>
                            <div class="card__review">
                                <a href="#"><img src="<?= $review->user->avatar?>" width="55" height="54"></a>
                                <div class="feedback-card__reviews-content">
                                    <p class="link-name link">
                                        <a href="#" class="link-regular"><?= $review->user->name?></a>
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

<!--                    <div class="feedback-card__reviews">-->
<!--                        <p class="link-task link">Задание <a href="#" class="link-regular">«Повесить полочку»</a></p>-->
<!--                        <div class="card__review">-->
<!--                            <a href="#"><img src="./img/woman-glasses.jpg" width="55" height="54"></a>-->
<!--                            <div class="feedback-card__reviews-content">-->
<!--                                <p class="link-name link"><a href="#" class="link-regular">Морозова Евгения</a></p>-->
<!--                                <p class="review-text">-->
<!--                                    Кумар приехал позже, чем общал и не привез с собой всех-->
<!--                                    инстументов. В итоге пришлось еще ходить в строительный магазин.-->
<!--                                </p>-->
<!--                            </div>-->
<!--                            <div class="card__review-rate">-->
<!--                                <p class="three-rate big-rate">3<span></span></p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>
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
    </div>
</main>
