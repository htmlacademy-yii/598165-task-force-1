<?php


namespace frontend\controllers;


use frontend\models\File;
use yii\web\NotFoundHttpException;

class UploadsController extends SecuredController
{
    /**
     * Uploads a file.
     * @param $id
     * @return \yii\console\Response|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionIndex($id) {
        $file = File::findOne($id);
        $path = \Yii::getAlias($file->src . '/' . $file->name);

        if (file_exists($path)) {
            return \Yii::$app->response->sendFile($path);
        }
        throw new NotFoundHttpException("Файл с ID $id не найден");
    }

}
