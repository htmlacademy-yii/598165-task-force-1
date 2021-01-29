<?php


namespace frontend\controllers;


use frontend\models\Event;

class EventsController extends SecuredController
{
    public function actionIndex () {
        $events = Event::find()
            ->where(['status' => Event::STATUS_NEW])
            ->andWhere(['addressee' => \Yii::$app->user->id])
            ->all();

        $eventList = [];
        $item =[];

        foreach ($events as $event) {
            $item['id'] = $event->id;
            $item['type'] = $event->type;
            $item['message'] = Event::TYPE[$event->type];
            $item['taskTitle'] = $event->task->title;
            array_push($eventList, $item);
        }

        return $this->asJson($eventList);
    }

    public function actionRead(int $id)
    {
        $event = Event::findOne($id);
        $event->status = Event::STATUS_READ;
        $event->save();
       return  $this->redirect(['tasks/view', 'id' => $event->task->id]);
    }

}

