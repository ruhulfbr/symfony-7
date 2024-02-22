<?php

// src/Event/EntityCreatedEvent.php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class EntityCreatedEvent extends Event
{
    public const NAME = 'entity.created';

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
