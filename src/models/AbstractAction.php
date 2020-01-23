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

    abstract protected function isAllowed(User $user, Task $task);
}
