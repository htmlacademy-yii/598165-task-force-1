<?php
return [
    'language' => 'ru-RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'formatter' => [
            'locale' => 'ru-RU',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'v1/messages/<task_id:\d+>' => 'v1/message/index',
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/message', 'v1/task']],

                '<controller:\w+>/<id:\d+>' => '<controller>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            ],
        ],

        'mapProvider' => [
            'class' => '\common\components\YandexMapProvider',
            'url' => 'https://api-maps.yandex.ru/2.1/',
            'lang' => 'ru_RU',
        ],

        'locationService' => [
            'class' => '\frontend\services\YandexLocationService',
            'url' => 'https://geocode-maps.yandex.ru/1.x',
            'lang' => 'ru_RU',
            'format' => 'json',
        ],
    ],
];
