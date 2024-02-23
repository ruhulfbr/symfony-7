<?php

// src/Message/HandleDeleteUser.php

namespace App\Message;

class DeleteUser
{
    public function __construct(private readonly int $userId)
    {

    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
