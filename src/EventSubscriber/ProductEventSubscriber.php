<?php

// src/EventSubscriber/ProductEventSubscriber.php

namespace App\EventSubscriber;

use App\Event\ProductCreatedEvent;
use App\Event\ProductUpdatedEvent;
use App\Event\ProductDeletedEvent;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ProductEventSubscriber implements EventSubscriberInterface
{

    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    // Returns an array indexed by event name and value by method name to call
    public static function getSubscribedEvents(): array
    {
        return [
            ProductCreatedEvent::NAME => 'onProductCreation',
            //hook multiple functions with the events with priority for sequence of function calls
            ProductUpdatedEvent::NAME => [
                ['afterProductUpdation', 1],
                ['onProductUpdation', 2],
            ],
            ProductDeletedEvent::NAME => 'onProductDeletion',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onProductCreation(ProductCreatedEvent $event):void
    {
        // write code to execute on product creation event

        $this->logger->info("Event Subscriber => Product Created");
    }

    public function onProductUpdation(ProductUpdatedEvent $event) :void
    {
        // write code to execute on product updation event

        $product = $event->getProduct();

        $this->logger->info("Event Subscriber => Product Updated. Id:  ".$product->getId());
    }

    public function afterProductUpdation(ProductUpdatedEvent $event):void
    {
        // write code to execute on product creation event

        $this->logger->info("Event Subscriber => Product after updated");
    }

    public function onProductDeletion(ProductDeletedEvent $event):void
    {
        // write code to execute on product deletion event

        $this->logger->info("Event Subscriber => Product Deleted");
    }

    public function onKernelResponse(ResponseEvent  $event):void
    {
        // write code to execute on in-built Kernel Response event
    }
}