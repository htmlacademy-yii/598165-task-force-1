<?php
/**
 * @var SignupForm $signupForm
 */

use frontend\models\City;
use frontend\models\SignupForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$currentCity = 'Химки';
?>
<section class="registration__user">
    <h1>Регистрация аккаунта</h1>


    <?php $form = ActiveForm::begin([
        'options' =>
            ['class' => 'registration__user-form form-create']
    ]); ?>

    <?= Html::activeLabel($signupForm, 'email',
        [
            'label' => 'Электронная почта',
            'class' => isset($signupForm->errors['email']) ? 'input-danger' : ''
        ]); ?>
    <?= Html::activeTextarea($signupForm, 'email',
        [
            'class' => 'input textarea',
            'rows' => '1',
            'placeholder' => 'kumarm@mail.ru'
        ]); ?>
    <?php if (isset($signupForm->errors['email'])) {
        foreach ($signupForm->errors['email'] as $errorMessage) {
            echo('<span>' . $errorMessage . '</span>');
        }
    } else {
        echo('<span>Введите валидный адрес электронной почты</span>');
    }
    ?>


    <?= Html::activeLabel($signupForm, 'username',
        [
            'label' => 'Ваше имя',
            'class' => isset($signupForm->errors['username']) ? 'input-danger' : ''
        ]); ?>
    <?= Html::activeTextarea($signupForm, 'username',
        [
            'class' => 'input textarea',
            'rows' => '1',
            'placeholder' => 'Мамедов Кумар'
        ]); ?>
    <span>Введите ваше имя и фамилию</span>

    <?= Html::activeLabel($signupForm, 'city',
        [
            'label' => 'Город проживания',
            'class' => isset($signupForm->errors['city']) ? 'input-danger' : ''
        ]) ?>
    <?= Html::activeDropDownList($signupForm, 'city',
        ArrayHelper::map(City::find()->asArray()->all(), 'name', 'name'),
        [
            'options' => [$currentCity => ['selected' => true]],
            'class' => 'multiple-select input town-select registration-town',
            'size' => '1',
        ]); ?>
    <span>Укажите город, чтобы находить подходящие задачи</span>

    <?= Html::activeLabel($signupForm, 'password',
        [
            'label' => 'Пароль',
            'class' => isset($signupForm->errors['password']) ? 'input-danger' : ''
        ]); ?>
    <?= Html::activePasswordInput($signupForm, 'password',
        [
            'class' => 'input textarea'
        ]) ?>
    <span>Длина пароля от 8 символов</span>

    <button class="button button__registration" type="submit">Cоздать аккаунт</button>

    <?php ActiveForm::end(); ?>
    </div>
</section>
