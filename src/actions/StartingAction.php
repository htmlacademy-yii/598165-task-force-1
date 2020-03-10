<?php

namespace TaskForce\actions;
use TaskForce\models\TaskAction;
use TaskForce\models\User;
use TaskForce\models\Task;

class StartingAction extends AbstractAction
{
    protected $internalName = TaskAction::START;
    protected $externalName = 'Откликнутся';

    public function isAllowed(User $user, Task $task): bool
    {
        return $user->id === $task->contractorId;
    }
}
