<?php

// src/MessageHandler/HandleSendWelcomeMail.php

namespace App\MessageHandler;

use App\Message\SendWelcomeEmail;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class HandleSendWelcomeMail
{
    public function __construct(private readonly LoggerInterface $logger)
    {

    }

    public function __invoke(SendWelcomeEmail $message): void
    {
        $userJsonData = $message->getContent();
        $userData = json_decode($userJsonData);

        $subject = "Welcome to JOB Queue World";
        $to = $userData->email;
        $msg = "Hello " . $userData->name . ", Welcome to JOB Queue World";

        $retryCount = 0;
        while ($retryCount < 3) {
            // $sent = mail($to, $subject, $msg);
            $sent = rand(0, 1);

            if ($sent) {
                print_r("Email Successfully Sent." . PHP_EOL);
                $this->logger->info("Email Successfully Sent." . PHP_EOL);
                return;
                
            } else {
                $retryCount++;
            }
        }

        $this->logger->error("Send Welcome Email: Failed to send email after 3 retries." . PHP_EOL);
        print_r("Failed to send email after 3 retries." . PHP_EOL);
    }
}
