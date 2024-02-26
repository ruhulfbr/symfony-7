<?php

// src/MessageHandler/HandleDeleteUser.php

namespace App\MessageHandler;

use App\Message\DeleteUser;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class HandleDeleteUser
{
    public function __construct(private readonly LoggerInterface $logger)
    {

    }

    public function __invoke(DeleteUser $message): void
    {
        $userId = $message->getUserId();
        print_r("Delete User Request for user ID = " . $userId . PHP_EOL);
        $this->logger->info("Delete User Request for user ID = " . $userId . PHP_EOL);

    }
}