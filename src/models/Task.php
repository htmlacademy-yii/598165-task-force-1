<?php

namespace TaskForce\models;

abstract class AbstractAction
{
    private $internalName;
    private $externalName;

    public function getInternalName()
    {
        return $this->internalName;
    }

    public function getExternalName()
    {
        return $this->externalName;
    };

    abstract protected function isAllowed(User $user, Task $task);
}

class StartAction extends AbstractAction
{
    private $internalName = TaskAction::START;
    private $externalName = 'Откликнутся';

    public function isAllowed(User $user, Task $task)
    {
        return $user->id === $task->contractor->id;
    }
}

class CancelAction extends AbstractAction
{
    private $internalName = TaskAction::CANCEL;
    private $externalName = 'Отменить';

    public function isAllowed(User $user, Task $task)
    {
        return $user->id === $task->client->id;
    }
}

class Task
{
    public $id;
    public $client;
    public $contractor;
    public $dueDate;
    public $description;

    private $status = TaskStatus::NEW;
    private $actions;


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

        $this->actions = [
        TaskStatus::NEW => [
            UserRole::CLIENT => new CancelAction(),
            UserRole::CONTRACTOR => new StartAction()
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


    }

    public function setContractor(User $user)
    {
        $this->contractor = $user;
    }

    public function getActionFor(User $user): ?string
    {
        if ($user->id === $this->client->id) {
            return $this->actions[$this->status][UserRole::CLIENT];
        }
        if ($user->id === $this->contractor->id) {
            return $this->actions[$this->status][UserRole::CONTRACTOR];
        }

        return null;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getNextStatus(string $action, User $user): ?string
    {
        if ($this->getActionFor($user) !== $action) {
            return null;
        }
        return $this->transitions[$action];
    }

    public function setNextStatus(string $action, User $user)
    {
        if ($this->getActionFor($user) !== $action) {
            return null;
        }
        $this->status = $this->transitions[$action];
    }
}
