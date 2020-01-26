<?php
namespace TaskForce\models;

class Strategy {
    public $available;

    public function __construct(int $clientId, ?User $contractor)
    {
        $this->available = [
            TaskStatus::NEW => [
                $clientId => new CancelAction(),
                $contractor => new StartAction()
            ],
            TaskStatus::CANCELED => [
                $clientId => null,
                $contractor->id => null
            ],
            TaskStatus::PENDING => [
                $clientId=> new FinishAction(),
                $contractor->id  => new RejectAction()
            ],
            TaskStatus::DONE => [
                $clientId => null,
                $contractor->id   => null
            ],
            TaskStatus::FAILED => [
                $clientId => null,
                $contractor->id  => null
            ]
        ];
    }
}
