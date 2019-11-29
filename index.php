<?php
namespace TaskForce\models;

require_once("vendor/autoload.php");

$client = new User(0);
$contractor = new User(1);
$randomUser = new User(2);

$task = new Task(1, $client, '', '');
$task->setContractor($contractor);

assert($task->getStatus() === TaskStatus::NEW,
    'A new task has the status "NEW"');
assert($task->getActionFor($client) === TaskAction::CANCEL,
    'A client has the action to "CANCEL" a new task');
assert($task->getActionFor($contractor) === TaskAction::START,
    'A contractor has the action to "START" a new task');
assert($task->getActionFor($randomUser) === '',
    'A random user doesn\'t have any actions');
assert($task->getNextStatus($task->getActionFor($client), $client) === TaskStatus::CANCELED,
    'The next task\'s status for a client action is "CANCELED"');
assert($task->getNextStatus($task->getActionFor($contractor), $contractor) === TaskStatus::PENDING,
    'The next task\'s status for a contractor action is "PENDING"');



