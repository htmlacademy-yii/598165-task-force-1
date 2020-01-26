<?php

namespace TaskForce\models;

class Task
{
    public $id;
    public $clientId;
    public $contractorId;
    public $dueDate;
    public $description;

    private $status = TaskStatus::NEW;

    public function __construct(int $id, int $clientId, string $dueDate, string $description)
    {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->dueDate = $dueDate;
        $this->description = $description;
    }

    public function setContractor(User $user)
    {
        $this->contractorId = $user->id;
    }

    public function getAction(User $user): ?AbstractAction
    {
        return $this->actions()[$this->status][$user->id] ?? null;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getNextStatus(AbstractAction $action, User $user): ?string
    {
        if ($this->getAction($user)->getInternalName() !== $action->getInternalName()) {
            return null;
        }
        return TaskAction::TRANSITION[$action->getInternalName()];
    }

    public function setNextStatus(string $action, User $user)
    {
        if ($this->getAction($user) !== $action) {
            return null;
        }
        $this->status = TaskAction::TRANSITION[$action];
    }

    private function actions()
    {
        return [
            TaskStatus::NEW => [
                $this->clientId => new CancelAction(),
                $this->contractorId => new StartAction()
            ],
            TaskStatus::CANCELED => [
                $this->clientId => null,
                $this->contractorId  => null
            ],
            TaskStatus::PENDING => [
                $this->clientId => new FinishAction(),
                $this->contractorId  => new RejectAction()
            ],
            TaskStatus::DONE => [
                $this->clientId  => null,
                $this->contractorId  => null
            ],
            TaskStatus::FAILED => [
                $this->clientId => null,
                $this->contractorId  => null
            ]
        ];
    }
}
