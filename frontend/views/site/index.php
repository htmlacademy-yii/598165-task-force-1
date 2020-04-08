<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
echo '<table style="width: 95%; border-collapse: collapse">';
foreach ($users as $user) {
    echo '<tr>';
    foreach ($user as $column) {
        echo '<td style="padding: 5px; border-bottom: 1px solid #ccc">' . $column . '</td>';
    }
    echo '</tr>';
}
echo '</table>';

?>
