<?php
/**
 * @var SignupForm $signupForm
 */

use frontend\models\City;
use frontend\models\SignupForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->params['citySelect'] = $this->context->citySelect;
?>

<section class="registration__user">

    <div class="registration-wrapper">
        <h1>Регистрация аккаунта</h1>

        <?php $form = ActiveForm::begin([
            'options' =>
                [
                    'class' => 'registration__user-form form-create',
                ],
            'fieldConfig' => [
                'hintOptions' => ['tag' => 'span']
            ]
        ]); ?>


        <?= $form->field($signupForm, 'email')
            ->textarea(
                [
                    'class' => 'input textarea',
                    'rows' => '1',
                    'placeholder' => 'kumarm@mail.ru',
                ])
            ->label('Электронная почта')
            ->hint('Введите валидный адрес электронной почты'); ?>

        <?= $form->field($signupForm, 'username')
            ->textarea(
                [
                    'class' => 'input textarea',
                    'rows' => '1',
                    'placeholder' => 'Мамедов Кумар',
                ])
            ->label('Ваше имя')
            ->hint('Введите ваше имя и фамилию'); ?>


        <?= $form->field($signupForm, 'city_id')
            ->dropDownList(
                ArrayHelper::map(City::find()->asArray()->all(), 'id', 'name'),
                [
                    'prompt' => ['text' => '', 'options' => ['value' => null]],
                    'class' => 'multiple-select input town-select registration-town',
                    'size' => '1',
                ])
            ->label('Город проживания')
            ->hint('Укажите город, чтобы находить подходящие задачи'); ?>


        <?= $form->field($signupForm, 'password')
            ->passwordInput(
                [
                    'class' => 'input textarea'
                ])
            ->label('Пароль')
            ->hint('Длина пароля от 8 символов'); ?>

        <?= Html::submitButton('Создать аккаунт', ['class' => 'button button__registration']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</section>

