<?php
declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

use TaskForce\utils\FixtureLoader;
use TaskForce\exceptions\FileFormatException;
use TaskForce\exceptions\SourceFileException;

require_once("vendor/autoload.php");

$filenames = [
    'data/categories.csv' => ['name', 'icon'],
    'data/cities.csv' => ['city', 'lat', 'long'],
    'data/opinions.csv' => ['dt_add', 'rate', 'description'],
    'data/profiles.csv' => ['address', 'bd', 'about', 'phone', 'skype'],
    'data/replies.csv' => ['dt_add', 'rate', 'description'],
    'data/tasks.csv' => ['dt_add', 'category_id', 'description', 'expire', 'name', 'address', 'budget', 'lat', 'long'],
    'data/users.csv' => ['email', 'name', 'password', 'dt_add']
];

foreach ($filenames as $filename => $header) {

    $loader = new FixtureLoader($filename, $header);

    try {
        $loader->import();
    } catch (SourceFileException $e) {
        echo("Fail to process csv file: " . $e->getMessage());
    } catch (FileFormatException $e) {
        echo("Wrong file format: " . $e->getMessage());
    }
}

