<?php
declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

use TaskForce\utils\FixtureLoader;
use TaskForce\exceptions\FileFormatException;
use TaskForce\exceptions\SourceFileException;

require_once("vendor/autoload.php");

$loader = new FixtureLoader(
    'data/profiles.csv',
    ['address', 'bd', 'about', 'phone', 'skype'],
    'user'
);

try {
    $loader->import();
} catch (SourceFileException $e) {
    error_log("Fail to process csv file: " . $e->getMessage());
} catch (FileFormatException $e) {
    error_log("Wrong file format: " . $e->getMessage());
}

try {
    $loader->writeSqlFlle('profiles');
} catch (SourceFileException $e) {
    error_log("Fail to process sql file" . $e->getMessage());
}
