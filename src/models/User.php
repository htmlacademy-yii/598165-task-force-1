<?php
declare(strict_types=1);

namespace TaskForce\models;

class User
{
    public $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
