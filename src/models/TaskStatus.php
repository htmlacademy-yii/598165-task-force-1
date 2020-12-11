<?php
declare(strict_types=1);

namespace TaskForce\models;

class TaskStatus
{
    const NEW = 'NEW';
    const CANCELED = 'CANCELED';
    const PENDING = 'PENDING';
    const DONE = 'DONE';
    const FAILED = 'FAILED';

    const STATUSES = [
        self::NEW => 'Новое',
        self::PENDING => 'В работе',
        self::CANCELED => 'Отменено',
        self::DONE => 'Завершено',
        self::FAILED => 'Провалено'
    ];
}
