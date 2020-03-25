<?php
declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

use TaskForce\utils\FixtureLoader;
use TaskForce\exceptions\FileFormatException;
use TaskForce\exceptions\SourceFileException;

require_once("vendor/autoload.php");

$filenames = [
    'data/categories.csv' => [
        'name' => 'category',
        'header'=> ['name', 'icon']
    ],
    'data/cities.csv' => [
        'name' => 'city',
        'header'=> ['city', 'lat', 'long']
    ],
    'data/opinions.csv' => [
        'name' => 'opinion',
        'header'=> ['dt_add', 'rate', 'description']
    ],
    'data/profiles.csv' => [
        'name' => 'profile',
        'header'=> ['address', 'bd', 'about', 'phone', 'skype']
    ],
    'data/replies.csv' => [
        'name' => 'reply',
        'header'=> ['dt_add', 'rate', 'description']
    ],
    'data/tasks.csv' => [
        'name' => 'task',
        'header'=> ['dt_add', 'category_id', 'description', 'expire', 'name', 'address', 'budget', 'lat', 'long']
    ],
    'data/users.csv' => [
        'name' => 'user',
        'header'=> ['email', 'name', 'password', 'dt_add']
    ]
];

foreach ($filenames as $filename => $table) {

    $loader = new FixtureLoader($filename, $table['name'], $table['header'] );

    try {
        $loader->import();
    } catch (SourceFileException $e) {
        echo("Fail to process csv file: " . $e->getMessage());
    } catch (FileFormatException $e) {
        echo("Wrong file format: " . $e->getMessage());
    }
}

