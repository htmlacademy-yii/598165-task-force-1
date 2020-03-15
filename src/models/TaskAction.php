<?php
declare(strict_types=1);

namespace TaskForce\models;

class TaskAction
{
    const START = 'START';
    const CANCEL = 'CANCEL';
    const REJECT = 'REJECT';
    const FINISH = 'FINISH';

    const TRANSITION = [
        TaskAction::START => TaskStatus::PENDING,
        TaskAction::CANCEL => TaskStatus::CANCELED,
        TaskAction::REJECT => TaskStatus::FAILED,
        TaskAction::FINISH => TaskStatus::DONE
    ];
}

