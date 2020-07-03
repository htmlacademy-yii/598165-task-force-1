<?php

/* @var $this \yii\web\View */


/* @var $content string */

use frontend\assets\AppAsset;
use frontend\widgets\AvatarWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$currentUser = Yii::$app->user->identity;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/style.css">
    <?php $this->registerCsrfMetaTags() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $content ?>

<footer class="page-footer">
    <div class="main-container page-footer__container">
        <div class="page-footer__info">
            <p class="page-footer__info-copyright">
                © 2019, ООО «ТаскФорс»
                Все права защищены
            </p>
            <p class="page-footer__info-use">
                «TaskForce» — это сервис для поиска исполнителей на разовые задачи.
                mail@taskforce.com
            </p>
        </div>
        <div class="page-footer__links">
            <ul class="links__list">
                <li class="links__item">
                    <a href="">Задания</a>
                </li>
                <li class="links__item">
                    <a href="">Мой профиль</a>
                </li>
                <li class="links__item">
                    <a href="">Исполнители</a>
                </li>
                <li class="links__item">
                    <a href="">Регистрация</a>
                </li>
                <li class="links__item">
                    <a href="">Создать задание</a>
                </li>
                <li class="links__item">
                    <a href="">Справка</a>
                </li>
            </ul>
        </div>
        <div class="page-footer__copyright">
            <a>
                <img class="copyright-logo"
                     src="/img/academy-logo.png"
                     width="185" height="63"
                     alt="Логотип HTML Academy">
            </a>
        </div>
        <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
