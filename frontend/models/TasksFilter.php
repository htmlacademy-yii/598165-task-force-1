<?php


namespace frontend\models;


use yii\base\Model;

class TasksFilter extends Model
{
    public $skills;
    public $additional;
    public $time;
    public $search;


    public  function rules()
    {
        return [
            [['skills', 'additional', 'time', 'search'], 'safe']
        ];
    }
}
