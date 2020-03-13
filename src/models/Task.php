<?php
declare(strict_types=1);

namespace TaskForce\models;
use TaskForce\actions\AbstractAction;
use TaskForce\actions\FinishAction;
use TaskForce\actions\RejectAction;
use TaskForce\actions\CancelAction;
use TaskForce\actions\StartingAction;

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

    public function setContractor(User $user):void
    {
        $this->contractorId = $user->id;
    }

    public function getAction(User $user): ?AbstractAction
    {
        return $this->actions()[$this->status][$user->id] ?? null;
    }

    public function getStatus():string
    {
        return $this->status;
    }

    public function getNextStatus(AbstractAction $action, User $user): ?string
    {
        if (!$action->isAllowed($user, $this)) {
            return null;
        }
        return TaskAction::TRANSITION[$action->getInternalName()];
    }

    public function setNextStatus(string $action, User $user): ?string
    {
        if ($this->getAction($user) !== $action) {
            return null;
        }
        $this->status = TaskAction::TRANSITION[$action];
    }

    private function actions(): array
    {
        return [
            TaskStatus::NEW => [
                $this->clientId => new CancelAction(),
                $this->contractorId => new StartingAction()
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

