<?php

namespace frontend\controllers;

use frontend\models\User;
use yii\db\Query;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $users = User::find()
            ->with('contractorTasks')
            ->with('reviews')
            ->with('skills')
            ->orderBy(['created_at' => SORT_ASC])
            ->all();

        return $this->render('index', ['users' => $users]);
    }
}

