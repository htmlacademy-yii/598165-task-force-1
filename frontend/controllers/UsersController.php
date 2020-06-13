<?php

namespace frontend\controllers;

use frontend\models\User;
use frontend\models\UsersFilter;
use frontend\models\UsersSorting;
use yii\db\Query;
use yii\web\Controller;

class UsersController extends Controller
{

    public function actionIndex($sort = UsersSorting::SORT_RATING)
    {
        $usersFilter = new UsersFilter();
        $usersSorting = new UsersSorting();

        $query = User::find()
            ->with(['contractorTasks', 'reviews', 'skills']);


        if (\Yii::$app->request->getIsPost()) {
            $request = \Yii::$app->request->post();

            if ($usersFilter->load($request) && $usersFilter->validate()) {
                $query = $usersFilter->applyFilters($query);
            }
        }

        $query = $usersSorting->applySorting($query, $sort);

        $users = $query->all();

        return $this->render('index',
            [
                'users' => $users,
                'usersFilter' => $usersFilter,
                'usersSorting' => $usersSorting,
            ]
        );
    }

    public function actionView(int $id) {
        $user = User::find()
            ->where(['id' => $id])
            ->with(['contractorTasks', 'reviews', 'skills'])
            ->one();
        return $this->render('view', ['user' => $user]);
    }
}

