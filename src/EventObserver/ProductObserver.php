<?php

// Src\EventObserver\ProductObserver.php

namespace App\EventObserver;

use App\Entity\Product;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;

use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostLoadEventArgs;

use Psr\Log\LoggerInterface;

class ProductObserver implements EventSubscriber
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::postPersist,
            Events::preUpdate,
            Events::postUpdate,
            Events::preRemove,
            Events::postRemove
            // Add more events as needed
        ];
    }

    public function prePersist(PrePersistEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Product) {
            return;
        }

        $entityManager = $args->getObjectManager();
        // ... do something with the Product entity

        $this->logger->info('AAAAAAAAAAAAAAAAAA prePersist: ' . $entity->getName());
    }

    public function postPersist(PostPersistEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Product) {
            return;
        }

        $entityManager = $args->getObjectManager();
        // ... do something with the Product entity

        $this->logger->info('BBBBBBBBBBBBBBBBB postPersist: ' . $entity->getName());
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Product) {
            return;
        }

        $entityManager = $args->getObjectManager();
        // ... do something with the Product entity

        $this->logger->info('CCCCCCCCCCCCCCCCCCCC preUpdate: ' . $entity->getName());
    }


    public function postUpdate(PostUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Product) {
            return;
        }

        $entityManager = $args->getObjectManager();
        // ... do something with the Product entity

        $this->logger->info('DDDDDDDDDDDDDDDDDDDDD postUpdate: ' . $entity->getName());
    }

    public function preRemove(PreRemoveEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Product) {
            return;
        }

        $entityManager = $args->getObjectManager();
        // ... do something with the Product entity

        $this->logger->info('EEEEEEEEEEEEEEEEEEEEEEE preRemove: ' . $entity->getName());
    }


    public function postRemove(PostRemoveEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Product) {
            return;
        }

        $entityManager = $args->getObjectManager();
        // ... do something with the Product entity

        $this->logger->info('FFFFFFFFFFFFFFFFFFFFFF postRemove: ' . $entity->getName());
    }
}