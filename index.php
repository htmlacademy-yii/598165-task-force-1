<?php
namespace TaskForce\models;

require_once("vendor/autoload.php");

$client = new User(0);
$contractor = new User(1);
$random_user = new User(2);

$task = new Task($client);
$contractor->setAsContractor($task);

echo $task->showNextStatusFor($client);
echo "</br>";
echo $task->showNextStatusFor($contractor);
echo "</br>";
echo $task->showNextStatusFor($random_user) ? $task->showNextStatusFor($random_user) : "No avaliable actions";
