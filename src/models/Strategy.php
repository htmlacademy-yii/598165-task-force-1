<?php
declare(strict_types=1);

namespace TaskForce\models;
use TaskForce\actions\CancelAction;
use TaskForce\actions\FinishAction;
use TaskForce\actions\StartingAction;
use TaskForce\actions\RejectAction;


class Strategy {
    public $available;

    public function __construct(int $clientId, ?int $contractorId)
    {
        $this->available = [
            TaskStatus::NEW => [
                $clientId => new CancelAction(),
                $contractorId => new StartingAction()
            ],
            TaskStatus::CANCELED => [
                $clientId => null,
                $contractorId => null
            ],
            TaskStatus::PENDING => [
                $clientId=> new FinishAction(),
                $contractorId  => new RejectAction()
            ],
            TaskStatus::DONE => [
                $clientId => null,
                $contractorId => null
            ],
            TaskStatus::FAILED => [
                $clientId => null,
                $contractorId => null
            ]
        ];
    }
}
