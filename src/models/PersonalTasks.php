<?php


namespace TaskForce\models;


class PersonalTasks
{
    const FILTER_COMPLETED = 'completed';
    const FILTER_NEW = 'new';
    const FILTER_PENDING = 'pending';
    const FILTER_CANCELED= 'canceled';
    const FILTER_EXPIRED = 'expired';

    const FILTERS = [
        self::FILTER_COMPLETED => 'Завершённые',
        self::FILTER_NEW => 'Новые',
        self::FILTER_PENDING => 'Активные',
        self::FILTER_CANCELED => 'Отменённые',
        self::FILTER_EXPIRED => 'Просроченные'
    ];

}
