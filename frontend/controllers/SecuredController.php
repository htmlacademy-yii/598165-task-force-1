<?php


namespace frontend\controllers;


use frontend\models\forms\CitySelect;
use yii\filters\AccessControl;
use yii\web\Controller;

class SecuredController extends Controller
{
    public ?CitySelect $citySelect = null;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->citySelect = new CitySelect();
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function($rule, $action) {
                    $this->goHome();
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

}
