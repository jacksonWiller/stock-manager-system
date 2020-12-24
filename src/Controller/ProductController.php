<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Product\ProductType;
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
     * @Route(name="product_create", methods={"POST"}, path="/api/product")
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
     * @Route(name="product_show", methods={"GET"}, path="/api/product/{id}")
     */
    public function showPlataforma(Product $product, Request $request)
    {
        try {
            $this->isGrantedAcl('uloc/product/show', $product->getId());

            return $this->createApiResponse($product, 200, null, 'public');
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="product_list", methods={"GET"}, path="/api/product")
     */
    public function listProduct(PersonManager $manager, Request $request)
    {
        try {
            $this->isGrantedAcl('uloc/product/list');

            list($page, $limit, $offset) = $this->getPagination($request, 100, self::MAX_RESULT_LIMIT);
            $filters = [];

            $this->bindFormEntityFilters($request, $filters);

            $products = $manager->listProduct(
                $limit,
                $offset,
                array_merge([
                    'sortBy' => $request->query->get('sortBy'),
                    'sortDesc' => $request->query->get('descending')
                ], $filters)
            ); // $manager->list($limit, $offset, ['role' => '']);
            // dump($plataformas);
            $products['result'] = $this->serialize($products['result'], 'array', 'admin', function (ApiRepresentationMetadata $metadata) {
                Product::loadApiRepresentation($metadata);
            });
            return $this->createApiResponseEncodeArray($products, 200);
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
        $form = $this->createForm(ProductType::class, $product);
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            throw new \Exception(serialize(['error' => 'validation', 'message' => $this->getErrorsFromForm($form)]));
        }
    }

}
