<?php

// src/Event/ProductCreatedEvent.php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ProductCreatedEvent extends Event
{
    public const NAME = 'product.created';

    protected $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function getProduct()
    {
        return $this->product;
    }
}
