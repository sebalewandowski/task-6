<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="send_action")
     */
    public function indexAction(Request $request)
    {
        return $this->render("index.html.twig");
    }

    /**
     * @Route("/create", name="product_show")
     */
    public function createAction(Request $request)
    {
        $category = new Category();
        $category->setName('Computer Peripherals');


        $product = new Product();
        $product->setName('ADASD');
        $product->setPrice(1.23);
        $product->setDescription('Ergonomic and stylish!');
        $product->setAvailability(1);

        // relates this product to the category
        $product->setCategory($category);

        $this->getDoctrine()->getManager()->persist($category);
        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();

        $repository = $this->getDoctrine()->getRepository(Product::class);

        $repository->find(1);

        return new JsonResponse(['name' => $product->getName(), 'price' => $product->getPrice(), 'category' => $category->getId()], 200);
    }

    /**
     * @Route("/product", name="product_show")
     */
    public function showAction(Request $request)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
$name = 'AD';
        $repository = $this->getDoctrine()->getRepository(Product::class)->findByPhrase($name);
        $jsonContent = $serializer->serialize($repository, 'json');

        return new Response($jsonContent, 200);
    }


//// look for multiple Product objects matching the name, ordered by price
//        $products = $repository->findBy(
//            ['availability' => 1]
//        );
//        return new Response(var_dump($categoryRepository));
////        $entityManager = $this->getDoctrine()->getManager();
////
////        $product = new Product();
////        $product->setName('Keyboard');
////        $product->setPrice(1999);
////        $product->setDescription('Ergonomic and stylish!');
////        $product->setAvailability(1);
////
////        // tell Doctrine you want to (eventually) save the Product (no queries yet)
////        $entityManager->persist($product);
////
////        // actually executes the queries (i.e. the INSERT query)
////        $entityManager->flush();
////
////        return new Response('Saved new product with id '.$product->getId());
//    }

}