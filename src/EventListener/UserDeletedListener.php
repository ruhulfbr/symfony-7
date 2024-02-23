<?php

// src/EventListener/UserCreatedListener.php

namespace App\EventListener;

use App\Entity\User;
use App\Event\EntityDeletedEvent;
use App\Message\DeleteUser;
use Symfony\Component\Messenger\MessageBusInterface;

class UserDeletedListener
{
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function onUserDeleted(EntityDeletedEvent $event): void
    {
        $entity = $event->getEntity();
        if ($entity instanceof User) {
            $userId = $entity->getId();
            $message = new DeleteUser($userId);
            $this->messageBus->dispatch($message);
        }
    }
}