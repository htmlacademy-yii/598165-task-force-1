<?php

namespace frontend\controllers;

use frontend\models\City;

use frontend\models\User;
use frontend\models\forms\UsersFilter;
use frontend\models\forms\UsersSorting;
use yii\web\NotFoundHttpException;

class UsersController extends SecuredController
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

    public function actionView(int $id)
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException("Пользователь с ID $id не найден");
        }

        return $this->render('view', [
            'user' => $user,
            'cities' => City::find()->asArray()->all(),
        ]);
    }
}

