<?php
namespace TaskForce\models;

require_once("vendor/autoload.php");

$client = new User(0);
$contractor = new User(1);
$randomUser = new User(2);

$task = new Task(1, $client);
$task->setContractor($contractor);

function testTask($task, $client, $contractor, $randomUser)
{
    echo 'The task has a stauts ' .
        $task->getStatus() . "<br />";

    echo 'Client has an action to ' .
        $task->getActionFor($client) . "<br />";

    echo 'Contractor has an action to ' .
        $task->getActionFor($contractor) . "<br />";

    echo $task->getActionFor($randomUser) ?:
        'No available actions for a random user<br />';

    echo 'Next task status for a client action is ' .
        $task->getNextStatus($task->getActionFor($client), $client) . "<br />";

    echo 'Next task status for a contractor action is ' .
        $task->getNextStatus($task->getActionFor($contractor), $contractor) .
        '<br/><br/>';
}

testTask($task, $client, $contractor, $randomUser);
$task->setNextStatus(TaskAction::START, $contractor);
testTask($task, $client, $contractor, $randomUser);


