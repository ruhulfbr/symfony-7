<?php

// src/EventListener/WelcomeMailSendFailureListener.php

namespace App\EventListener;

use App\Message\SendWelcomeEmail;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;

class WelcomeMailSendFailureListener
{
    public function __construct(private readonly LoggerInterface $logger)
    {

    }

    public function onMailSendFailed(WorkerMessageFailedEvent $event): void
    {
        if ($event->willRetry()) {
            return;
        }

        $message = $event->getEnvelope()->getMessage();
        if ($message instanceof SendWelcomeEmail) {
            $this->logger->error(
                'Failed to send welcome email after 3 retries',
                [
                    'exception' => $event->getThrowable(),
                    'message' => $message,
                ]
            );
        }
    }
}