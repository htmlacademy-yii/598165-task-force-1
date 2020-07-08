<?php

/* @var int $currentCity
 * @var array $cities
 */


use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>

<?= Html::dropDownList('town',
    $currentCity,
    ArrayHelper::map($cities, 'id', 'name'),
    [
        'class' => 'multiple-select input town-select',
        'size' => '1',
        'onchange' => 'this.form.submit()'
    ]);
?>
