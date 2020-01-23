<?php

namespace TaskForce\models;

class FinishAction extends AbstractAction
{
    protected $internalName = TaskAction::FINISH;
    protected $externalName = 'Завершить';

    public function isAllowed(User $user, Task $task)
    {
        return $user->id === $task->client->id;
    }
}
