<?php

namespace App\Controller;

use App\Entity\MyEntity;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: index(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();
        
        $product = new MyEntity();
        $createdAt = "10-01-1982";
        $product->setTitle('Loop');
        $product->setContent('gdsgfthfjnftrjrtjtjrj');
        $product->setCreatedAt(new \DateTime($createdAt));
        $product->setIsEnabled(false);
        $product->setNbLike(100);
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        //return new Response('Saved new product with id '.$product->getId());

        return $this->render('product/index.html.twig',[
            'product' => $product,
            ]);


    }
}
