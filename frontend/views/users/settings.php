<?php
/* @var $settingsForm SettingsForm */

use frontend\assets\SettingsAsset;
use frontend\models\City;
use frontend\models\forms\SettingsForm;
use frontend\models\Skill;
use frontend\models\User;
use frontend\widgets\AvatarWidget;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

SettingsAsset::register($this);
$user = User::findOne(\Yii::$app->user->id);
?>

<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <?php $form = ActiveForm::begin([
        'id' => 'account',
        'method' => 'post',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]) ?>

    <div class="account__redaction-section">
        <h3 class="div-line">Настройки аккаунта</h3>
        <div class="account__redaction-section-wrapper">
            <div class="account__redaction-avatar">

                <?= AvatarWidget::widget(['user' => $user, 'width' => 156, 'height' => 156]) ?>

                <?= $form->field($settingsForm, 'avatar', [
                    'options' => [
                        'tag' => false,
                    ]
                ])->fileInput([
                    'id' => 'upload-avatar'
                ])->label('Сменить аватар', ['class' => 'link-regular']);
                ?>
            </div>

            <div class="account__redaction">

                <div class="account__input account__input--name">
                    <label for="200">Ваше имя</label>
                    <input class="input textarea" id="200" name="" placeholder="<?= \Yii::$app->user->identity->name?>" disabled>
                </div>


                <?= $form->field($settingsForm, 'email', [
                    'options' => [
                        'class' => 'account__input account__input--email'
                    ]
                ])->input('email', [
                    'class' => 'input textarea'
                ])->label('email')
                ?>

                <?= $form->field($settingsForm, 'city_id', [
                    'options' => [
                        'class' => 'account__input account__input--name'
                    ]
                ])->dropDownList(ArrayHelper::map(City::find()->asArray()->all(), 'id', 'name'), [
                    'class' => 'multiple-select input multiple-select-big'
                ])->label('Город') ?>

                <?= $form->field($settingsForm, 'birthday_at')->input('date', [
                    'class' => 'input-middle input input-date'
                ])->label('День рождения');
                ?>

                <?= $form->field($settingsForm, 'about', [
                    'options' => [
                        'class' => 'account__input account__input--info'
                    ]
                ])->textarea([
                    'class' => 'input textarea',
                    'rows' => 7
                ])->label('Информация о себе');
                ?>
            </div>
        </div>
        <h3 class="div-line">Выберите свои специализации</h3>
        <div class="account__redaction-section-wrapper">
            <?= $form->field($settingsForm, 'skills', [
                'template' => "{input}",
                'options' => [
                    'class' => 'search-task__categories account_checkbox--bottom',
                ]
            ])->checkboxList(Skill::getFormFields(), [
                'tag' => false
            ]);
            ?>
        </div>
        <h3 class="div-line">Безопасность</h3>
        <div class="account__redaction-section-wrapper account__redaction">

            <?= $form->field($settingsForm, 'password_new', [
                'options' => [
                    'class' => 'account__input'
                ]
            ])->passwordInput([
                'class' => 'input textarea'
            ])->label('Новый пароль');
            ?>

            <?= $form->field($settingsForm, 'password_repeat', [
                'options' => [
                    'class' => 'account__input'
                ]
            ])->passwordInput([
                'class' => 'input textarea'
            ])->label('Повтор пароля');
            ?>

        </div>

        <h3 class="div-line">Фото работ</h3>
        <div class="account__redaction-section-wrapper account__redaction">

            <div class="dropzone"></div>
        </div>

        <h3 class="div-line">Контакты</h3>
        <div class="account__redaction-section-wrapper account__redaction">

            <?= $form->field($settingsForm, 'phone', [
                'options' => [
                    'class' => 'account__input'
                ]
            ])->input('phone', [
                'class' => 'input textarea',
            ])->label('Телефон');
            ?>

            <?= $form->field($settingsForm, 'skypeid', [
                'options' => [
                    'class' => 'account__input'
                ]
            ])->textInput([
                'class' => 'input textarea',
            ])->label('Skype');
            ?>

            <?= $form->field($settingsForm, 'messenger', [
                'options' => [
                    'class' => 'account__input'
                ]
            ])->textInput([
                'class' => 'input textarea',
            ])->label('Мессенджер');
            ?>

        </div>
        <h3 class="div-line">Настройки сайта</h3>
        <h4>Уведомления</h4>
        <div class="account__redaction-section-wrapper account_section--bottom">
            <div class="search-task__categories account_checkbox--bottom">

                <?= $form->field($settingsForm, 'is_notify_message', [
                    'template' => "{input}\n{label}",
                    'options' => [
                        'tag' => false,
                    ]
                ])
                    ->checkbox(['class' => 'visually-hidden checkbox__input'], false)
                    ->label('Новое сообщение'); ?>

                <?= $form->field($settingsForm, 'is_notify_action', [
                    'template' => "{input}\n{label}",
                    'options' => [
                        'tag' => false,
                    ]
                ])
                    ->checkbox(['class' => 'visually-hidden checkbox__input'], false)
                    ->label('Действия по заданию'); ?>

                <?= $form->field($settingsForm, 'is_notify_review', [
                    'template' => "{input}\n{label}",
                    'options' => [
                        'tag' => false,
                    ]
                ])
                    ->checkbox(['class' => 'visually-hidden checkbox__input'], false)
                    ->label('Новый отзыв'); ?>

            </div>
            <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                <?= $form->field($settingsForm, 'is_show_only_owner', [
                    'template' => "{input}\n{label}",
                    'options' => [
                        'tag' => false,
                    ]
                ])
                    ->checkbox(['class' => 'visually-hidden checkbox__input'], false)
                    ->label('Показывать мои контакты только заказчику'); ?>

                <?= $form->field($settingsForm, 'is_hidden', [
                    'template' => "{input}\n{label}",
                    'options' => [
                        'tag' => false,
                    ]
                ])
                    ->checkbox(['class' => 'visually-hidden checkbox__input'], false)
                    ->label('Не показывать мой профиль'); ?>

            </div>
        </div>
    </div>
    <button class="button" type="submit">Сохранить изменения</button>

    <?php ActiveForm::end(); ?>

</section>

