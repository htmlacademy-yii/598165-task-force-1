<?php

/* @var $this yii\web\View
 * @var \frontend\models\Task $task
 * @var City[] $cities
 * @var  ResponseTaskForm $responseTaskForm
 * @var FinishTaskForm $finishTaskForm
 */


use frontend\models\City;
use frontend\models\forms\FinishTaskForm;
use frontend\models\forms\ResponseTaskForm;
use frontend\models\Response;
use frontend\widgets\AvatarWidget;
use frontend\widgets\MapWidget;
use frontend\widgets\StarRatingWidget;
use TaskForce\models\TaskStatus;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'TaskForce - Task';


$currentUser = \Yii::$app->user->identity;
\frontend\assets\ViewTaskAsset::register($this);
?>

<section class="content-view">

    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?= $task->title ?></h1>
                    <span>Размещено в категории
                                <a
                                    href="<?= Url::to(['/tasks', 'TasksFilter[skills][]' => $task->skill_id])?>"
                                    class="link-regular">
                                    <?= $task->skill->name ?>
                                </a>
                                <?= \Yii::$app->formatter->asRelativeTime($task->created_at) ?>
                            </span>
                </div>
                <?php if ($task->budget) : ?>
                    <b class="new-task__price new-task__price--clean content-view-price">
                        <?= $task->budget ?><b> ₽</b>
                    </b>
                <?php endif; ?>
                <div class="new-task__icon new-task__icon--<?= $task->skill->icon ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p>
                    <?= $task->description ?>
                </p>
            </div>

            <?php if (count($task->files)) : ?>
                <div class="content-view__attach">
                    <h3 class="content-view__h3">Вложения</h3>
                    <?php foreach ($task->files as $file) {
                        echo Html::a($file->name, [$file->getPath()]);
                    } ?>
                </div>
            <?php endif; ?>

            <?php if (isset($task->city_id)) : ?>

                <div class="content-view__location">
                    <h3 class="content-view__h3">Расположение</h3>
                    <div class="content-view__location-wrapper">

                        <?= MapWidget::widget([
                            'longitude' => $task->longitude,
                            'latitude' => $task->latitude,
                            'width' => '361px',
                            'height' => '292px',
                            'zoom' => 16,
                        ]); ?>

                        <div class="content-view__address">
                        <span class="address__town">
                            <?= $task->city_id ? City::findOne($task->city_id)->name : ''; ?>
                        </span><br>
                            <span>
                            <?= $task->address ?>
                        </span>
                            <!--                        <p>Вход под арку, код домофона 1122</p>-->
                        </div>
                    </div>
                </div

            <?php endif; ?>

        </div>
        <div class="content-view__action-buttons">


            <?php
            $nextAction = $task->actions()[$currentUser->getRole()];
            if ($nextAction->isAllowed($currentUser, $task)) {
                echo Html::button($nextAction->getExternalName(),
                    [
                        'class' => 'button button__big-color ' . $nextAction->getInternalName() . '-button open-modal',
                        'data-for' => $nextAction->getInternalName() . '-form'
                    ]);
            }
            ?>

        </div>
    </div>
    <?php if (count($task->responses)) : ?>
        <div class="content-view__feedback">
            <?= ($currentUser->id === $task->client_id) ?
                '<h2>Отклики <span>(' . count($task->responses) . ')</span></h2>' : ''; ?>

            <div class="content-view__feedback-wrapper">
                <?php foreach ($task->responses as $response): ?>
                    <?php if ($currentUser->id === $task->client_id ||
                        $currentUser->id === $response->user_id) : ?>
                        <div class="content-view__feedback-card">
                            <div class="feedback-card__top">
                                <a href="<?= Url::to([
                                    'users/view',
                                    'id' => $response->user->id
                                ]); ?>">
                                    <?= AvatarWidget::widget([
                                        'user_id' => $response->user->id,
                                        'width' => 55,
                                        'height' => 55
                                    ]); ?>
                                </a>
                                <div class="feedback-card__top--name">
                                    <p>
                                        <?= Html::a($response->user->name,
                                            ['users/view', 'id' => $response->user->id],
                                            ['class' => 'link-regular']); ?>
                                    </p>
                                    <?= StarRatingWidget::widget(['rating' => $response->user->rating]) ?>
                                </div>
                                <span class="new-task__time">
                                <?= \Yii::$app->formatter->asRelativeTime($response->created_at) ?>
                            </span>
                            </div>
                            <div class="feedback-card__content">
                                <p>
                                    <?= $response->description ?>
                                </p>
                                <span><?= $response->budget ? $response->budget . ' ₽' : ''; ?></span>
                            </div>

                            <?php if ($currentUser->id === $task->client_id && $task->status === TaskStatus::NEW &&
                                $response->status === Response::PENDING) : ?>
                                <div class="feedback-card__actions">

                                    <?= Html::a('Подтвердить', [
                                        'tasks/accept',
                                        'id' => $response->id
                                    ],
                                        ['class' => 'button__small-color request-button button']) ?>

                                    <?= Html::a('Отказаться', [
                                        'tasks/decline',
                                        'id' => $response->id
                                    ],
                                        ['class' => 'button__small-color refusal-button button']) ?>

                                    <button class="button__chat button"
                                            type="button"></button>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
<?php if ($task->status !== TaskStatus::NEW) : ?>
    <section class="connect-desk">
        <?php if ($currentUser->id === $task->client_id) {
            $user = $task->contractor;
            $header = 'Исполнитель';
        } else {
            $user = $task->client;
            $header = 'Заказчик';
        } ?>
        <div class="connect-desk__profile-mini">
            <div class="profile-mini__wrapper">
                <h3><?= $header ?></h3>
                <div class="profile-mini__top">
                    <?= AvatarWidget::widget([
                        'user_id' => $user->id,
                        'width' => 62,
                        'height' => 62
                    ]); ?>
                    <div class="profile-mini__name five-stars__rate">
                        <p><?= $user->name ?></p>
                        <?= StarRatingWidget::widget(['rating' => $user->rating]) ?>
                    </div>
                </div>
                <p class="info-customer">
                    <span><?= count($user->reviews) ?> отзывов</span>
                    <span class="last-"><?= $user->numberOfCompletedTasks?>  заказов</span>
                </p>
                <?= Html::a('Смотреть профиль', ['users/view', 'id' => $user->id]); ?>
            </div>
        </div>
        <div id="chat-container">
            <chat class="connect-desk__chat" task="<?= $task->id ?>"></chat>
        </div>
    </section>
<?php endif; ?>
<section class="modal response-form form-modal" id="response-form">
    <h2>Отклик на задание</h2>

    <?php
    $form = ActiveForm::begin([
        'action' => ['tasks/response', 'id' => $task->id],
        'fieldConfig' => [
            'template' => "<p>{label}\n{input}</p>{error}",
            'labelOptions' => [
                'class' => 'form-modal-description'
            ],
            'errorOptions' => [
                'class' => 'help-block'
            ]
        ],
    ]);
    ?>
    <?= $form->field($responseTaskForm, 'payment')->input('text', [
        'class' => 'response-form-payment input input-middle input-money',
    ]) ?>
    <?= $form->field($responseTaskForm, 'comment')->textarea([
        'class' => 'input textarea',
        'rows' => 4,
        'placeholder' => 'Place your text'
    ]) ?>
    <?= Html::submitButton('Отправить', [
        'class' => 'button modal-button'
    ]) ?>
    <?php ActiveForm::end(); ?>

    <button class="form-modal-close" type="button">Закрыть</button>
</section>

<section class="modal completion-form form-modal" id="complete-form">
    <h2>Завершение задания</h2>
    <p class="form-modal-description">Задание выполнено?</p>
    <?php $form = ActiveForm::begin([
        'id' => 'finishForm',
        'action' => ['tasks/finish', 'id' => $task->id],
        'fieldConfig' => [
            'template' => "<p>{label}\n{input}</p>{error}",
            'labelOptions' => [
                'class' => 'form-modal-description'
            ],
            'errorOptions' => [
                'class' => 'help-block'
            ]
        ]

    ]); ?>

    <?= Html::activeRadioList($finishTaskForm, 'completion',
        FinishTaskForm::getCompletionFields(), [
            'item' => function ($index, $label, $name, $checked, $value) {
                $radio = Html::radio($name, $checked, [
                    'id' => $value,
                    'value' => $value,
                    'class' => 'visually-hidden completion-input completion-input--' . $value
                ]);
                $label = Html::label($label, $value, [
                    'class' => 'completion-label completion-label--' . $value
                ]);

                return $radio . $label;
            },
            'unselect' => null
        ]); ?>

    <?= Html::error($finishTaskForm, 'completion', ['class' => 'help-block']) ?>

    <?= $form->field($finishTaskForm, 'comment')->textarea([
        'class' => 'input textarea',
        'rows' => 4,
        'placeholder' => 'Place your text'
    ]); ?>


    <?= $form->field($finishTaskForm, 'rating', [
        'template' =>
            "<p>{label}
              <div class='feedback-card__top--name completion-form-star'>
                <span class='star-disabled'></span>
                <span class='star-disabled'></span>
                <span class='star-disabled'></span>
                <span class='star-disabled'></span>
                <span class='star-disabled'></span>
              </div>
          {input}</p>{error}"
    ])->hiddenInput(['id' => 'rating']); ?>

    <?= Html::submitButton('Отправить', [
        'class' => 'button modal-button'
    ]) ?>

    <?php ActiveForm::end(); ?>


    <button class="form-modal-close" type="button">Закрыть</button>
</section>

<section class="modal form-modal refusal-form" id="refusal-form">
    <h2>Отказ от задания</h2>
    <p>
        Вы собираетесь отказаться от выполнения задания.
        Это действие приведёт к снижению вашего рейтинга.
        Вы уверены?
    </p>
    <button class="button__form-modal button" id="close-modal"
            type="button">Отмена
    </button>

    <?= Html::a('Отказаться',
        ['tasks/reject', 'id' => $task->id],
        ['class' => 'button__form-modal refusal-button button']) ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>

<section class="modal form-modal refusal-form" id="cancel-form">
    <h2>Отказ от задания</h2>

    <button class="button__form-modal button" id="close-modal"
            type="button">Отмена
    </button>

    <?= Html::a('Отказаться',
        ['tasks/cancel', 'id' => $task->id],
        ['class' => 'button__form-modal refusal-button button']) ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>

