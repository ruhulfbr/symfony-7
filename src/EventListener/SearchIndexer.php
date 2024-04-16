<?php

// src/EventListener/SearchIndexer.php
namespace App\EventListener;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

#[AsDoctrineListener(event: Events::postPersist, priority: 500, connection: 'default')]
class SearchIndexer
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    // the listener methods receive an argument which gives you access to
    // both the entity object of the event and the entity manager itself
    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Product) {
            return;
        }

        $entityManager = $args->getObjectManager();
        // ... do something with the Product entity

        $this->logger->info('888888888888888888888 New product created: ' . $entity->getName());
    }
}