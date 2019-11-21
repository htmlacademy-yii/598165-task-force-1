<?php
namespace TaskForce\models;
use TaskForce\constants\TaskStatus;
use TaskForce\constants\UserRole;

class Task
{
    public $client;
    public $contractor;

    private $current_status = TaskStatus::NEW;

    private $available_statuses = ([
        TaskStatus::NEW => ([
            UserRole::CLIENT => TaskStatus::CANCELED,
            UserRole::CONTRACTOR => TaskStatus::IN_PROGRESS
            ]),
        TaskStatus::CANCELED => ([
            UserRole::CLIENT,
            UserRole::CONTRACTOR
            ]),
        TaskStatus::IN_PROGRESS => ([
            UserRole::CLIENT => TaskStatus::DONE,
            UserRole::CONTRACTOR => TaskStatus::FAILED
            ]),
        TaskStatus::DONE => ([
            UserRole::CLIENT,
            UserRole::CONTRACTOR
            ]),
        TaskStatus::FAILED => ([
            UserRole::CLIENT,
            UserRole::CONTRACTOR
        ])
    ]);

    public function __construct(User $client)
    {
        $this->client = $client;
    }

    public function showNextStatusFor(User $user)
    {
        if ($user != $this->client and $user != $this->contractor) {
            return null;
        }

        return $this->available_statuses[$this->current_status][$user->role];
    }
}
