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
                '<controller:\w+>/<id:\d+>' => '<controller>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            ],
        ],
        'mapProvider' => [
            'class' => '\common\components\ServiceProvider',
            'url' => 'https://api-maps.yandex.ru/2.1/',
            'apiKeyName' => 'apiKey',
            'query' => [
                'lang' => 'ru_RU',
            ]
        ],
        'locationProvider' => [
            'class' => '\common\components\ServiceProvider',
            'url' => 'https://geocode-maps.yandex.ru/1.x',
            'apiKeyName' => 'apikey',
            'addressKeyName' => 'geocode',
            'query' => [
                'lang' => 'ru_RU',
                'format' => 'json',
            ],
        ],
    ],
];
