<?php
namespace TaskForce\classes;

class User {
    public $id = null;
    public $role = UserRole::CLIENT;
    private $task = null;

    public function __construct($id) {
        $this->id = $id;
    }

    public function set_as_contractor($task) {
        $this->role = UserRole::CONTRACTOR;
        $this->task = $task;
        $task->contractor = $this;
    }

}
