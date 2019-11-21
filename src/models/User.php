<?php
namespace TaskForce\models;
use TaskForce\constants\UserRole;

class User {
    public $id;
    public $role = UserRole::CLIENT;

    private $task;

    public function __construct(int $id) {
        $this->id = $id;
    }

    public function setAsContractor(Task $task) {
        $this->role = UserRole::CONTRACTOR;
        $this->task = $task;
        $task->contractor = $this;
    }

}
