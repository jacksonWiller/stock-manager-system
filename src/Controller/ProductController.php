<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Product\ProductType as ProductProductType;
use App\Form\ProductType;
use Doctrine\ORM\Query\Expr\Base;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Uloc\ApiBundle\Controller\BaseController;
use Uloc\ApiBundle\Manager\PersonManager;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadata;
use Doctrine\ORM\EntityManagerInterface;



class ProductController extends BaseController
{
    /**
     * @Route(name="product_show", methods={"POST"}, path="/test/product")
     */
    public function newProduct(Request $request, EntityManagerInterface $entityManager)
    {
     $plataforma = new Product();
     $plataforma->setName($request->get('nome'));
     
     
     $entityManager->persist($plataforma);
     $entityManager->flush();
 
     $plataforma = [
      'status' => 200,
      'success' => "Plataforma added successfully",
     ];
     return $this->createApiResponse($plataforma, 200);
    }

    /**
     * @Route(name="product_create", methods={"POST"}, path="/product")
     */
    public function newPlataforma(Request $request, PersonManager $manager)
    {
        try {
            $this->isGrantedAcl('uloc/product/create');
            $product = new Product();
            $this->processProductForm($product, $request);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->createApiResponse($product, 201);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @param Prduct $product
     * @param Request $request
     * @throws \Exception
     */
    protected function processProductForm(Product $product, Request $request, $update = false)
    {
        $form = $this->createForm(ProductProductType::class, $product);
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            throw new \Exception(serialize(['error' => 'validation', 'message' => $this->getErrorsFromForm($form)]));
        }
    }


}
