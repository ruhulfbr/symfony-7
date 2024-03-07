<?php

namespace App\Service;

class Intersection implements Traversable, Countable
{
    public function one(): string
    {
        return "From Interface Traverseable";
    }

    public function two(): string
    {
        return "From Interface Countable";
    }
}