<?php

namespace frontend\controllers;

use frontend\models\forms\SettingsForm;
use frontend\models\UserHasFiles;
use frontend\models\City;

use frontend\models\File;
use frontend\models\User;
use frontend\models\forms\UsersFilter;
use frontend\models\forms\UsersSorting;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

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

    public function actionSettings()
    {
        $session = \Yii::$app->session;

        $src = \Yii::getAlias('@webroot/uploads/user') . \Yii::$app->user->id;
        FileHelper::createDirectory($src);

        if (\Yii::$app->request->isAjax) {

            $files = UploadedFile::getInstancesByName('file');
            $sessionFiles = $session['files'];

            foreach ($files as $file) {
                try {
                    $file->saveAs($src . "/$file->name");

                } catch (\Throwable $e) {
                    throw $e;
                }
                $sessionFiles[] = $file->name;
            }

            $session['files'] = $sessionFiles;
            return $this->asJson(['answer' => 'OK', 'files' => $session['files']]);

        }

        $settingsForm = new SettingsForm();

        if (\Yii::$app->request->isPost) {

            if ($settingsForm->load(\Yii::$app->request->post()) && $settingsForm->save()) {
                if (!empty($session['files'])) {

                    $user = User::findOne(\Yii::$app->user->id);
                    $oldFiles = $user->files;

                    foreach ($oldFiles as $file) {
                        $file->delete();
                    }
                    UserHasFiles::deleteAll(['user_id' => $user->id]);

                    foreach ($session['files'] as $file) {

                        $transaction = \Yii::$app->db->beginTransaction();
                        try {
                            $newFile = new File();
                            $newFile->name = $file;
                            $newFile->src = 'uploads/user' . \Yii::$app->user->id;
                            $newFile->save();

                            $relation = new UserHasFiles();
                            $relation->user_id = \Yii::$app->user->id;
                            $relation->file_id = $newFile->id;
                            $relation->save();

                            $transaction->commit();
                        } catch (\Throwable $e) {
                            $transaction->rollBack();
                            throw $e;
                        }

                    }
                    unset($session['files']);
                }

                return $this->redirect('index');
            }

        }

        if (!empty($session['files'])) {
            unset($session['files']);

        }

        return $this->render('settings', [
            'settingsForm' => $settingsForm
        ]);
    }

}

