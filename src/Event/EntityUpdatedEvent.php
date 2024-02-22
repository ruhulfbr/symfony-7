<?php

// src/Event/EntityUpdatedEvent.php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class EntityUpdatedEvent extends Event
{
    public const NAME = 'entity.updated';

    protected $entity;
    protected $dirtyData;

    public function __construct($entity, $dirtyData)
    {
        $this->dirtyData = $dirtyData;
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getDirtyData()
    {
        return $this->dirtyData;
    }
}
