<?php
declare(strict_types=1);

namespace TaskForce\actions;
use TaskForce\models\TaskAction;
use frontend\models\Task;
use frontend\models\User;

class RejectAction extends AbstractAction
{
    protected $internalName = 'refusal';
    protected $externalName = 'Отказаться';

    public function isAllowed(User $user, Task $task): bool
    {
        return $user->id === $task->contractor_id;
    }
}
