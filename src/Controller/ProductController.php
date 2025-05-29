<?php

namespace App\Controller;

use App\Entity\Product;
use App\Event\ProductCreatedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="app_product")
     */
    public function index(EventDispatcherInterface $dispatcher): Response
    {
        $product = new Product();
        $product->setName('Symfony Book');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        $event = new ProductCreatedEvent($product);
        $dispatcher->dispatch($event,ProductCreatedEvent::name);
        return new Response('Product created and event dispatched!');

//        return $this->render('product/index.html.twig', [
//            'controller_name' => 'ProductController',
//        ]);


    }
}
