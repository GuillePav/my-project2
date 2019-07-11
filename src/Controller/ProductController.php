<?php

namespace App\Controller;

use App\Form\ProductType;
use App\Entity\MyEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckBoxType;
use Symfony\Component\HttpFoundation\Request;
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

        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);


    }

    /**
     * @Route("/product/{id}", name="editProduct", requirements = {"id"="\d+"})
     */

    public function editProductAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(MyEntity::class);
        $product = $repository->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('product/editProduct.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product/new", name="newProduct")
     */

    public function newProductFormAction(Request $request)
    {
        $product = new MyEntity();

        /*
        En créant directement le formulaire dans le controller :
        $form = $this->createFormBuilder($product)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('created_at', DateType :: class)
            //->add('is_enabled', ChoiceType::class, array('choices' => array(
            //    'Yes' => '1',
            //    'No' => '2')))
            ->add('is_enabled', CheckBoxType :: class)
            ->add('nb_like', IntegerType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Product'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            // do some actions with the data, save it, etc.
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            //return $this->redirectToRoute('editProduct', array('id' => $article->getId()));
            return $this->render('product/task_success.html.twig', [
                'product' => $product
            ]);
        }

        return $this->render('product/new.html.twig',
            [     'form' => $form->createView(),

              ]);
    */
        //En utilisant une class de formulaire :

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            //$this->addFlash('success', 'Article Created!');
            return $this->redirectToRoute('editProduct', [
                'id' => $product->getId()
            ]);
        }

        return $this->render('product/new.html.twig',
            ['form' => $form->createView(),

            ]);

    }

    /**
     * @Route("/product/update/{id}", name="updateProduct", requirements ={"id"="\d+"})
     */

    public function updateProductAction($id, Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(MyEntity::class)->find($id);


        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();


            return $this->redirectToRoute('editProduct', array('id' => $product->getId())
            );
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function createDeleteForm(MyEntity $product)
    {
        //on crée un formulaire
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('editProduct', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->add('delete', SubmitType::class)
            ->getForm()
            ;
    }

    /**
     * @Route("/product/delete/{id}", name="deleteProduct", requirements ={"id"="\d+"})
     */

    public function deleteProductAction($id, Request $request)
    {

    }



}
