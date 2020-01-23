<?php

namespace TaskForce\models;

class StartAction extends AbstractAction
{
    protected $internalName = TaskAction::START;
    protected $externalName = 'Откликнутся';

    public function isAllowed(User $user, Task $task)
    {
        return $user->id === $task->contractor->id;
    }
}
