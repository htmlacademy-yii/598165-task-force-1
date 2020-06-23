<?php
/**
 * @var SignupForm $signupForm
 */

use frontend\models\City;
use frontend\models\SignupForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<section class="registration__user">

    <div class="registration-wrapper">
        <h1>Регистрация аккаунта</h1>

        <?php $form = ActiveForm::begin([
            'options' =>
                [
                    'class' => 'registration__user-form form-create',
                ],
            'enableClientScript' => false,
        ]); ?>


        <?= $form->field($signupForm, 'email', [
            'options' => ['tag' => false],
            'errorOptions' => ['tag' => 'span', 'class' => false, 'style' => 'color: #FF116E'],
            'addAriaAttributes' => false,
        ])
            ->textarea(
                [
                    'class' => 'input textarea',
                    'rows' => '1',
                    'placeholder' => 'kumarm@mail.ru',
                ])
            ->label('Электронная почта')
            ->hint('Введите валидный адрес электронной почты',
                [
                    'tag' => 'span',
                    'class' => false
                ]); ?>

        <?= $form->field($signupForm, 'username', [
            'options' => ['tag' => false],
            'errorOptions' => ['tag' => 'span', 'class' => false, 'style' => 'color: #FF116E'],
            'addAriaAttributes' => false,
        ])
            ->textarea(
                [
                    'class' => 'input textarea',
                    'rows' => '1',
                    'placeholder' => 'Мамедов Кумар',
                ])
            ->label('Электронная почта')
            ->hint('Введите ваше имя и фамилию',
                [
                    'tag' => 'span',
                    'class' => false
                ]); ?>


        <?= $form->field($signupForm, 'city_id', [
            'options' => ['tag' => false],
            'errorOptions' => ['tag' => 'span', 'class' => false, 'style' => 'color: #FF116E'],
            'addAriaAttributes' => false,
        ])
            ->dropDownList(
                ArrayHelper::map(City::find()->asArray()->all(), 'id', 'name'),
                [
                    'prompt' => ['text' => '', 'options' => ['value' => null]],
                    'class' => 'multiple-select input town-select registration-town',
                    'size' => '1',
                ])
            ->label('Город проживания')
            ->hint('Укажите город, чтобы находить подходящие задачи',
                [
                    'tag' => 'span',
                    'class' => false
                ]); ?>


        <?= $form->field($signupForm, 'password', [
            'options' => ['tag' => false],
            'errorOptions' => ['tag' => 'span', 'class' => false, 'style' => 'color: #FF116E'],
            'addAriaAttributes' => false,
        ])
            ->passwordInput(
                [
                    'class' => 'input textarea'
                ])
            ->label('Пароль')
            ->hint('Длина пароля от 8 символов',
                [
                    'tag' => 'span',
                    'class' => false
                ]); ?>

        <?= Html::submitButton('Создать аккаунт', ['class' => 'button button__registration']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</section>

