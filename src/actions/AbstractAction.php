<?php

namespace TaskForce\actions;
use TaskForce\models\User;
use TaskForce\models\Task;

abstract class AbstractAction
{
    protected $internalName;
    protected $externalName;

    /**
     * Method returns an action's internal name
     *
     * @return string
     */
    public function getInternalName(): string
    {
        return $this->internalName;
    }

    /**
     * Method returns an action's external name
     *
     * @return string
     */
    public function getExternalName(): string
    {
        return $this->externalName;
    }

    /**
     * Method checks if a user can operate on a task
     *
     * Abstract method that must be implemented in sub-class
     *
     * @param User $user
     * @param Task $task
     * @return boolean
     */
    abstract protected function isAllowed(User $user, Task $task): bool;
}
