<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

Class ProductUpdatedEvent extends event{
    public const NAME = 'product.updated';
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