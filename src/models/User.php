<?php

namespace TaskForce\models;

class User
{
    public $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
