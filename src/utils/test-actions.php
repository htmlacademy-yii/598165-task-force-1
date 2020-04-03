<?php
declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

use TaskForce\models\User;
use TaskForce\models\Task;
use TaskForce\models\TaskStatus;
use TaskForce\models\TaskAction;


require_once("vendor/autoload.php");

$client = new User(0);
$contractor = new User(1);
$randomUser = new User(2);

$task = new Task(1, $client->id, '', '');
$task->setContractor($contractor);

try {
    assert($task->getStatus() === TaskStatus::NEW,
        'A new task has the status "NEW"');

    assert($task->getAction($client)->getInternalName() === TaskAction::CANCEL,
        'A client has the action to "CANCEL" a new task');

    assert($task->getAction($contractor)->getInternalName() === TaskAction::START,
        'A contractor has the action to "START" a new task');

    assert($task->getAction($randomUser) === null,
        'A random user doesn\'t have any actions');

    assert($task->getNextStatus($task->getAction($client), $client) === TaskStatus::CANCELED,
        'The next task\'s status for a client action is "CANCELED"');

    assert($task->getNextStatus($task->getAction($contractor), $contractor) === TaskStatus::PENDING,
        'The next task\'s status for a contractor action is "PENDING"');
} catch (\TaskForce\exceptions\IllegalActionException $e) {
    error_log("Action error " . $e->getMessage());
} catch (\TaskForce\exceptions\ForbiddenActionException $e) {
    error_log("Action error " . $e->getMessage());
}



