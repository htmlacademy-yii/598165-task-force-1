<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\LandingAsset;

LandingAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>TaskForce</title>
    <?php $this->head(); ?>
</head>

<?php $this->beginBody() ?>
<body class="landing">
<div class="table-layout">
    <?= $content ?>
    <?=  \Yii::$app->view->renderFile('@app/views/layouts/footer.php');?>
</div>
<div class="overlay"></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
