<?php
declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

use TaskForce\utils\FixtureLoader;
use TaskForce\exceptions\FileFormatException;
use TaskForce\exceptions\SourceFileException;

require_once(__DIR__ . "/vendor/autoload.php");

$filenames = [
    'cities.csv' => [
        'name' => 'city',
        'header' => ['name', 'latitude', 'longitude']
    ],
    'categories.csv' => [
        'name' => 'skill',
        'header' => ['name', 'icon']
    ],
    'users-profiles.csv' => [
        'name' => 'user',
        'header' => [
            'email',
            'name',
            'password',
            'created_at',
            'city_id',
            'address',
            'birthday_at',
            'about',
            'phone',
            'skypeid'
        ]
    ],
    'tasks.csv' => [
        'name' => 'task',
        'header' => [
            'created_at',
            'skill_id',
            'client_id',
            'description',
            'due_date_at',
            'title',
            'address',
            'budget',
            'latitude',
            'longitude'
        ]
    ],
    'opinions.csv' => [
        'name' => 'review',
        'header' => [
            'created_at',
            'user_id',
            'task_id',
            'rating',
            'description'
        ]
    ],
    'replies.csv' => [
        'name' => 'response',
        'header' => [
            'created_at',
            'user_id',
            'task_id',
            'rate',
            'description'
        ]
    ],
    'user-has-skill.csv' => [
        'name' => 'user_has_skill',
        'header' => [
            'user_id',
            'skill_id'
        ]
    ]
];

foreach ($filenames as $filename => $table) {

    $filename = __DIR__ . '/data/' . $filename;

    $loader = new FixtureLoader($filename, $table['name'], $table['header']);

    try {
        $loader->import();
    } catch (SourceFileException $e) {
        echo("Fail to process csv file: " . $e->getMessage());
    } catch (FileFormatException $e) {
        echo("Wrong file format: " . $e->getMessage() . " " . $filename);
    }
}

