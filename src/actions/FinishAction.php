<?php
declare(strict_types=1);

namespace TaskForce\actions;
use TaskForce\models\TaskAction;
use frontend\models\Task;
use frontend\models\User;

class FinishAction extends AbstractAction
{
    protected $internalName = 'complete';
    protected $externalName = 'Завершить';

    public function isAllowed(User $user, Task $task): bool
    {
        return $user->id === $task->client_id;
    }
}
