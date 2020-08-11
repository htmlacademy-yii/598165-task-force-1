<?php
declare(strict_types=1);

namespace TaskForce\actions;
use TaskForce\models\TaskAction;
use frontend\models\Task;
use frontend\models\User;

class StartingAction extends AbstractAction
{
    protected $internalName = 'response';
    protected $externalName = 'Откликнутся';

    public function isAllowed(User $user, Task $task): bool
    {
//        return $user->id === $task->contractor_id;
        return !$user->hasRespondedOnTask($task);
    }
}
