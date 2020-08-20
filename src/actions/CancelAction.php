<?php
declare(strict_types=1);

namespace TaskForce\actions;
use frontend\models\Task;
use frontend\models\User;
use TaskForce\models\TaskAction;


class CancelAction extends AbstractAction
{
    protected $internalName = TaskAction::CANCEL;
    protected $externalName = 'Отменить';

    public function isAllowed(User $user, Task $task): bool
    {
        return $user->id === $task->client_id;
    }
}
