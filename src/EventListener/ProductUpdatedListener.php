<?php

// src/EventListener/ProductUpdatedListener.php

namespace App\EventListener;

use App\Event\ProductUpdatedEvent;
use Psr\Log\LoggerInterface;

class ProductUpdatedListener
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function onProductUpdated(ProductUpdatedEvent $event): void
    {
        $product = $event->getProduct();

        // Your logic to handle the product creation event

        // For example, log the product data
        $this->logger->info('Existing product updated: ' . $product->getName());
    }
}
