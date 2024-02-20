<?php

// src/EventListener/ProductCreatedListener.php

namespace App\EventListener;

use App\Event\ProductCreatedEvent;
use Psr\Log\LoggerInterface;

class ProductCreatedListener
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function onProductCreated(ProductCreatedEvent $event): void
    {
        $product = $event->getProduct();

        // Your logic to handle the product creation event

        // For example, log the product data
        $this->logger->info('99999999999999999999999999999 New product created: ' . $product->getName());
    }
}
