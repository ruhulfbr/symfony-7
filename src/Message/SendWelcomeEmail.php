<?php

// src/Message/SendWelcomeEmail.php

namespace App\Message;

class SendWelcomeEmail
{
    public function __construct(private readonly string $content)
    {

    }

    public function getContent(): string
    {
        return $this->content;
    }
}
