<?php
namespace TaskForce\classes;

class Task
{
    public $client = null;
    public $contractor = null;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function show_next_status_for($user)
    {
        if ($user != $this->client and $user != $this->contractor) {
            return null;
        }

        return $this->available_statuses[$this->current_status][$user->role];
    }

    private $current_status = TaskStatus::NEW;

    private $available_statuses = array(
        TaskStatus::NEW => array(
            UserRole::CLIENT => TaskStatus::CANCELED,
            UserRole::CONTRACTOR => TaskStatus::IN_PROGRESS
            ),
        TaskStatus::CANCELED => array(
            UserRole::CLIENT => null,
            UserRole::CONTRACTOR => null
            ),
        TaskStatus::IN_PROGRESS => array(
            UserRole::CLIENT => TaskStatus::DONE,
            UserRole::CONTRACTOR => TaskStatus::FAILED
            ),
        TaskStatus::DONE => array(
            UserRole::CLIENT => null,
            UserRole::CONTRACTOR => null
            ),
        TaskStatus::FAILED => array(
            UserRole::CLIENT => null,
            UserRole::CONTRACTOR => null
            )
        );
}
