<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

Class ProductDeletedEvent extends event{
    public const NAME = 'product.deleted';
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