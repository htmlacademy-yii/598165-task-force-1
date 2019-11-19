<?php
namespace TaskForce\classes;

require_once("vendor/autoload.php");



// require_once("src/classes/Task.php");
// require_once("src/classes/User.php");


$client = new User(0);
$contractor = new User(1);
$random_user = new User(2);

$task = new Task($client);
$contractor->set_as_contractor($task);

echo $task->show_next_status_for($client);
echo "</br>";
echo $task->show_next_status_for($contractor);
echo "</br>";
echo $task->show_next_status_for($random_user);
