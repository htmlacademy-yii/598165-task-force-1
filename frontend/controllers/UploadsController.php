<?php


namespace frontend\controllers;


use frontend\models\File;
use yii\web\NotFoundHttpException;

class UploadsController extends SecuredController
{
    public function actionIndex($id) {
        $file = File::findOne($id);
        $src = \Yii::getAlias('@app/uploads/'.$file->name);

        if (file_exists($src)) {
            return \Yii::$app->response->sendFile($src);
        }
        throw new NotFoundHttpException("Файл с ID $id не найден");
    }

}
