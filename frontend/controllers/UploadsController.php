<?php


namespace frontend\controllers;


use frontend\models\File;
use Yii;

use yii\web\NotFoundHttpException;
use yii\web\Response;

class UploadsController extends SecuredController
{
    /**
     * Uploads a file.
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionIndex($id): Response
    {
        $file = File::findOne($id);
        $path = Yii::getAlias($file->src . '/' . $file->name);

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        }
        throw new NotFoundHttpException("Файл с ID $id не найден");
    }

}
