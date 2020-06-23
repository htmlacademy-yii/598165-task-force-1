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
                ['class' => 'registration__user-form form-create'],
            'enableClientScript' => false,
        ]); ?>


        <?= $form->field($signupForm, 'email', [
            'options' => ['tag' => false],
            'errorOptions' => ['tag' => 'span', 'class' => false],
            'addAriaAttributes' => false,
            'template' => isset($signupForm->errors['email']) ? "{label}\n{input}\n{error}" : "{label}\n{input}\n{hint}"
        ])
            ->textarea(
                [
                    'class' => 'input textarea',
                    'rows' => '1',
                    'placeholder' => 'kumarm@mail.ru',
                ])
            ->label('Электронная почта',
                [
                    'class' => isset($signupForm->errors['email']) ? 'input-danger' : null
                ])
            ->hint('Введите валидный адрес электронной почты',
                [
                    'tag' => 'span',
                    'class' => false
                ]); ?>

        <?= $form->field($signupForm, 'username', [
            'options' => ['tag' => false],
            'errorOptions' => ['tag' => 'span', 'class' => false],
            'addAriaAttributes' => false,
            'template' => isset($signupForm->errors['username']) ? "{label}\n{input}\n{error}" : "{label}\n{input}\n{hint}"
        ])
            ->textarea(
                [
                    'class' => 'input textarea',
                    'rows' => '1',
                    'placeholder' => 'Мамедов Кумар',
                ])
            ->label('Электронная почта',
                [
                    'class' => isset($signupForm->errors['username']) ? 'input-danger' : null
                ])
            ->hint('Введите ваше имя и фамилию',
                [
                    'tag' => 'span',
                    'class' => false
                ]); ?>


        <?= $form->field($signupForm, 'city', [
            'options' => ['tag' => false],
            'errorOptions' => ['tag' => 'span', 'class' => false],
            'addAriaAttributes' => false,
            'template' => isset($signupForm->errors['username']) ? "{label}\n{input}\n{error}" : "{label}\n{input}\n{hint}"
        ])
            ->dropDownList(
                ArrayHelper::map(City::find()->asArray()->all(), 'name', 'name'),
                [
                    'prompt' => ['text' => '', 'options' => ['value' => 'none']],
                    'class' => 'multiple-select input town-select registration-town',
                    'size' => '1',
                ])
            ->label('Город проживания',
                [
                    'class' => isset($signupForm->errors['city']) ? 'input-danger' : null
                ])
            ->hint('Укажите город, чтобы находить подходящие задачи',
                [
                    'tag' => 'span',
                    'class' => false
                ]); ?>


        <?= $form->field($signupForm, 'password', [
            'options' => ['tag' => false],
            'errorOptions' => ['tag' => 'span', 'class' => false],
            'addAriaAttributes' => false,
            'template' => isset($signupForm->errors['password']) ? "{label}\n{input}\n{error}" : "{label}\n{input}\n{hint}"
        ])
            ->passwordInput(
                [
                    'class' => 'input textarea'
                ])
            ->label('Пароль',
                [
                    'class' => isset($signupForm->errors['password']) ? 'input-danger' : null
                ])
            ->hint('Длина пароля от 8 символов',
                [
                    'tag' => 'span',
                    'class' => false
                ]); ?>

        <?= Html::submitButton('Создать аккаунт', ['class' => 'button button__registration']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</section>

