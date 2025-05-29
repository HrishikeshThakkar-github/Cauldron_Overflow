<?php

namespace App\Event;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductCreatedEvent extends Event
{
    public const name = "product_created";

    public function __construct(Product $product){
    }

    public function getProduct(): Product{
        return $this->product;
    }

}