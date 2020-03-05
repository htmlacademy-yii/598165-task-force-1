<?php

namespace TaskForce\actions;
use TaskForce\models\TaskAction;
use TaskForce\models\User;
use TaskForce\models\Task;

class CancelAction extends AbstractAction
{
    protected $internalName = TaskAction::CANCEL;
    protected $externalName = 'Отменить';

    public function isAllowed(User $user, Task $task): bool
    {
        return $user->id === $task->clientId;
    }
}
