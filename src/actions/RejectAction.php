<?php

namespace TaskForce\actions;
use TaskForce\models\TaskAction;
use TaskForce\models\Task;
use TaskForce\models\User;

class RejectAction extends AbstractAction
{
    protected $internalName = TaskAction::REJECT;
    protected $externalName = 'Отказаться';

    public function isAllowed(User $user, Task $task): bool
    {
        return $user->id === $task->contractorId;
    }
}
