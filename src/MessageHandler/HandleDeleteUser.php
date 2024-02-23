<?php

// src/MessageHandler/HandleDeleteUser.php

namespace App\MessageHandler;

use App\Message\DeleteUser;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class HandleDeleteUser
{
    public function __invoke(DeleteUser $message): void
    {
        $userId = $message->getUserId();
        print_r("Delete User Request for user ID = " . $userId . PHP_EOL);

    }
}