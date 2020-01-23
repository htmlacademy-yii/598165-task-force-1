<?php

namespace TaskForce\models;

class CancelAction extends AbstractAction
{
    protected $internalName = TaskAction::CANCEL;
    protected $externalName = 'Отменить';

    public function isAllowed(User $user, Task $task)
    {
        return $user->id === $task->client->id;
    }
}

