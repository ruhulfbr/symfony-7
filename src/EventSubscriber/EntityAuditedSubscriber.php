<?php
// src/EventSubscriber/EntityAuditedSubscriber.php
namespace App\EventSubscriber;

use App\Entity\EntityAudited;
use App\Event\EntityCreatedEvent;
use App\Event\EntityDeletedEvent;
use App\Event\EntityUpdatedEvent;
use App\Repository\EntityAuditedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EntityAuditedSubscriber implements EventSubscriberInterface
{

    private LoggerInterface $logger;
    private $entityAudit;
    private $entityManager;

    public function __construct(LoggerInterface $logger, EntityAuditedRepository $entityAudit, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityAudit = $entityAudit;
        $this->entityManager = $entityManager;
    }

    // Returns an array indexed by event name and value by method name to call
    public static function getSubscribedEvents(): array
    {
        return [
            EntityCreatedEvent::NAME => 'onCreation',
            EntityUpdatedEvent::NAME => 'onUpdate',
            EntityDeletedEvent::NAME => 'onDelete'
        ];
    }

    public function onCreation(EntityCreatedEvent $event): void
    {
        $entity = $event->getEntity();
        $this->handleAudit($entity, "Created");
    }

    public function onUpdate(EntityUpdatedEvent $event): void
    {
        $entity = $event->getEntity();

        $dirtyData = $event->getDirtyData();
        $this->handleAudit($entity, "Updated", $dirtyData);
    }

    public function onDelete(EntityDeletedEvent $event): void
    {
        $entity = $event->getEntity();
        $this->handleAudit($entity, "Deleted");
    }

    private function handleAudit($entity, $action, $dirtyData = null): void
    {
        $this->logger->info("Entity Audited => Entity " . $action . ". Id: " . $entity->getId());

        $entityMetadata = $this->entityManager->getClassMetadata(get_class($entity));
        $entityType = $entityMetadata->getReflectionClass()->getShortName();

        if ($action == 'Updated') {
            $entityData = $this->getUpdatedData($dirtyData);
        } else {
            $entityData = $this->getRowData($entity, $entityMetadata);
        }

        $audit = new EntityAudited();
        $audit->setData(json_encode($entityData)); // Encoding extracted data
        $audit->setEvent($action);
        $audit->setCreatedAt(new \DateTime());
        $audit->setEntityType($entityType);
        $audit->setEntityId($entity->getId());

        $this->entityManager->persist($audit);
        $this->entityManager->flush();
    }

    private function getUpdatedData($dirtyData): array
    {
        $updateData = [];
        if (!empty($dirtyData)) {
            foreach ($dirtyData as $key => $value) {
                $updateData[$key] = $value[1];
            }
        }

        return $updateData;
    }

    private function getRowData($entity, $entityMetadata): array
    {
        $excludedFields = ['id', 'created_at', 'updated_at'];

        $fieldNames = $entityMetadata->getFieldNames();

        $entityData = [];
        foreach ($fieldNames as $fieldName) {
            if (!in_array($fieldName, $excludedFields)) {
                $entityData[$fieldName] = $entityMetadata->getFieldValue($entity, $fieldName);
            }
        }

        return $entityData;
    }
}