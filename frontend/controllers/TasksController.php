<?php

namespace frontend\controllers;

use frontend\models\Skill;
use frontend\models\Task;

use frontend\models\TasksFilter;
use TaskForce\models\TaskStatus;
use yii\db\Query;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $filter = new TasksFilter();
        $skills = Skill::find()->select(['name'])->orderBy('id')->column();

        if (\Yii::$app->request->getIsPost()) {

            $request = \Yii::$app->request->post();

            if ($filter->load($request)) {
                $filter->attributes = $request['TasksFilter'];
            }
        }

        $query = Task::find()
            ->with('city')->with('skill')
            ->orderBy(['created_at' => SORT_DESC]);

        if (!empty($filter->attributes['skills'])) {
            $query->where(['skill_id' => $filter->attributes['skills']]);
        }

        if (!empty($filter->attributes['additional'])) {
            if (in_array('withoutResponses', $filter->attributes['additional'])) {
//                $query->join('LEFT JOIN','review', 'review.task_id != task.id');
            }
            if (in_array('remoteWork', $filter->attributes['additional'])) {
                $query->andWhere(['city_id' => null]);
            }
        }

        $tasks = $query->all();

//        print_r($filter->attributes['skills']);
//        print_r($filter->attributes['additional']);

        return $this->render('index', [
                'tasks' => $tasks,
                'filter' => $filter,
                'skills' => $skills,
            ]
        );
    }
}

