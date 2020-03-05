<?php

namespace TaskForce\actions;
use TaskForce\models\TaskAction;
use TaskForce\models\User;
use TaskForce\models\Task;

class FinishAction extends AbstractAction
{
    protected $internalName = TaskAction::FINISH;
    protected $externalName = 'Завершить';

    public function isAllowed(User $user, Task $task): bool
    {
        return $user->id === $task->clientId;
    }
}
