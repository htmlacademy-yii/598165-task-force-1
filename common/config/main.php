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
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'locale' => 'ru-RU',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'api/messages/<task_id:\d+>' => 'api/message/index',
                ['class' => 'yii\rest\UrlRule', 'controller' => ['api/message', 'api/task']],

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
