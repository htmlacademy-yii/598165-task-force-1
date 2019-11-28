<?php

namespace TaskForce\models;

class Task
{
    public $id;
    public $client;
    public $contractor;
    public $dueDate;
    public $description;

    private $status = TaskStatus::NEW;

    private $actions = [
        TaskStatus::NEW => [
            UserRole::CLIENT => TaskAction::CANCEL,
            UserRole::CONTRACTOR => TaskAction::START
        ],
        TaskStatus::CANCELED => [
            UserRole::CLIENT,
            UserRole::CONTRACTOR
        ],
        TaskStatus::PENDING => [
            UserRole::CLIENT => TaskAction::FINISH,
            UserRole::CONTRACTOR => TaskAction::REJECT
        ],
        TaskStatus::DONE => [
            UserRole::CLIENT => null,
            UserRole::CONTRACTOR => null
        ],
        TaskStatus::FAILED => [
            UserRole::CLIENT => null,
            UserRole::CONTRACTOR => null
        ]
    ];

    private $transitions = [
        TaskAction::START => TaskStatus::PENDING,
        TaskAction::CANCEL => TaskStatus::CANCELED,
        TaskAction::REJECT => TaskStatus::FAILED,
        TaskAction::FINISH => TaskStatus::DONE
    ];

    public function __construct(int $id, User $client, string $dueDate, string $description)
    {
        $this->id = $id;
        $this->client = $client;
        $this->dueDate = $dueDate;
        $this->description = $description;
    }

    public function setContractor(User $user)
    {
        $this->contractor = $user;
    }

    public function getActionFor(User $user): string
    {
        if ($user->id === $this->client->id) {
            return $this->actions[$this->status][UserRole::CLIENT];
        }
        if ($user->id === $this->contractor->id) {
            return $this->actions[$this->status][UserRole::CONTRACTOR];
        }

        return "";
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getNextStatus(string $action, User $user): string
    {
        if ($this->getActionFor($user) !== $action) {
            return "";
        }
        return $this->transitions[$action];
    }

    public function setNextStatus(string $action, User $user)
    {
        if ($this->getActionFor($user) !== $action) {
            return;
        }
        $this->status = $this->transitions[$action];
    }
}
