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
    private $isCompleted = false;

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
            UserRole::CLIENT,
            UserRole::CONTRACTOR
            ],
        TaskStatus::FAILED => [
            UserRole::CLIENT,
            UserRole::CONTRACTOR
        ]
    ];

    private $transitions = [
        TaskAction::START => TaskStatus::PENDING,
        TaskAction::CANCEL => TaskStatus::CANCELED,
        TaskAction::REJECT => TaskStatus::FAILED,
        TaskAction::FINISH => TaskStatus::DONE
    ];

    public function __construct(int $id, User $client)
    {
        $this->id = $id;

        $this->client = $client;
        $this->client->roles[$this->id] = UserRole::CLIENT;
    }

    public function setContractor(User $user)
    {
        $this->contractor = $user;
        $this->contractor->roles[$this->id] = UserRole::CONTRACTOR;
    }

    public function getActionFor(User $user)
    {
        if (!isset($user->roles[$this->id])) {
            return;
        }
        return $this->actions[$this->status][$user->roles[$this->id]];
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getNextStatus(string $action, User $user)
    {
        if ($this->getActionFor($user) !== $action) {
            return;
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
