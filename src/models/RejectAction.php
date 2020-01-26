<?php

namespace TaskForce\models;

class RejectAction extends AbstractAction
{
    protected $internalName = TaskAction::REJECT;
    protected $externalName = 'Отказаться';

    public function isAllowed(User $user, Task $task): bool
    {
        return $user->id === $task->contractor->id;
    }
}
