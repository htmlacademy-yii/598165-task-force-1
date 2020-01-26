<?php

namespace TaskForce\models;

abstract class AbstractAction
{
    protected $internalName;
    protected $externalName;

    public function getInternalName()
    {
        return $this->internalName;
    }

    public function getExternalName()
    {
        return $this->externalName;
    }

    /**
     * Method checks if user can operate on a task
     *
     * Abstract method that must be implemented in sub-class
     *
     * @param User $user
     * @param Task $task
     * @return boolean
     */
    abstract protected function isAllowed(User $user, Task $task): bool;
}
