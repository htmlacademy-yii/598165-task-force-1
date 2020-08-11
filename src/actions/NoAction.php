<?php


namespace TaskForce\actions;


use frontend\models\Task;
use frontend\models\User;

class NoAction extends AbstractAction
{
    protected $internalName = null;
    protected $externalName = null;

    public function isAllowed(User $user, Task $task): bool
    {
        return false;
    }

}
