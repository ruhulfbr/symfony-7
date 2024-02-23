<?php

// src/EventListener/UserCreatedListener.php

namespace App\EventListener;

use App\Entity\User;
use App\Event\EntityCreatedEvent;
use App\Message\SendWelcomeEmail;
use Symfony\Component\Messenger\MessageBusInterface;

class UserCreatedListener
{
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }
    
    public function onUserCreated(EntityCreatedEvent $event): void
    {
        $entity = $event->getEntity();
        if ($entity instanceof User) {
            $messageData = [
                'name' => $entity->getName(),
                'email' => $entity->getEmail()
            ];
            $message = new SendWelcomeEmail(json_encode($messageData));
            $this->messageBus->dispatch($message);
        }
    }
}