<?php

// src/Event/EntityDeletedEvent.php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class EntityDeletedEvent extends Event
{
    public const NAME = 'entity.deleted';

    protected $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }
}
