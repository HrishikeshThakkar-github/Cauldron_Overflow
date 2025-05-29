<?php

namespace App\EventListener;

use App\Event\ProductCreatedEvent;

class ProductCreatedEventListener{

    public function onProductCreated(ProductCreatedEvent $event){
        $product = $event->getProduct();
        dump("hello world");
    }
}