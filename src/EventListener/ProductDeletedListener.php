<?php

// src/EventListener/ProductDeletedListener.php

namespace App\EventListener;

use App\Event\ProductDeletedEvent;
use Psr\Log\LoggerInterface;

class ProductDeletedListener
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function onProductDeleted(ProductDeletedEvent $event): void
    {
        $product = $event->getProduct();

        // Your logic to handle the product creation event

        // For example, log the product data
        $this->logger->info('Existing product Deleted: ' . $product->getName());
    }
}