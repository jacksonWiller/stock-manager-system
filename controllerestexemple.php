<?php

namespace App\Controller\Plataforma;

use App\Controller\ControllerTrait;
 use App\Entity\Plataforma;
 use App\Repository\PlataformaRepository;
 use Doctrine\ORM\EntityManagerInterface;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\JsonResponse;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\Routing\Annotation\Route;
 use Uloc\ApiBundle\Controller\BaseController;
 use Uloc\ApiBundle\Serializer\ApiRepresentationMetadata;
 use Uloc\ApiBundle\Api\ApiProblem;
 use Uloc\ApiBundle\Api\ApiProblemException;
 use App\Traits\UserHelper;

  /**
  * @Route("/")
  */
 class PlataformaController extends BaseController
 {

    Use UserHelper;
    Use ControllerTrait;
  
/**
 * @Route("/plataforma/{id}", name="show_plataforma", methods={"GET"})
 */
public function showPlataforma($id)
{
    $plataforma = $this->getDoctrine()
    ->getRepository(Plataforma::class)
    ->find($id);

    return $this->createApiResponse($plataforma, 200);
}


   /**
   * @Route("/plataforma", name="plataformas_create", methods={"POST"})
   */
  public function newPlataforma(Request $request, EntityManagerInterface $entityManager)
  {
 
 
     $plataforma = new Plataforma();
     $plataforma->setNome($request->get('nome'));
     $plataforma->setDominio($request->get('dominio'));
     $plataforma->setDominiosAdicionais($request->get('dominiosAdicionais'));
     $plataforma->setModoCadastro($request->get('modoCadastro'));
     $plataforma->setPrazoDoacaoUT($request->get('prazoDoacaoUT'));
     $plataforma->setHabilitarEntrada($request->get('habilitarEntrada'));

     
     $entityManager->persist($plataforma);
     $entityManager->flush();
 
     $plataforma = [
      'status' => 200,
      'success' => "Plataforma added successfully",
     ];
     return $this->createApiResponse($plataforma, 200);
 
 
   }

    /**

   * @Route("/plataforma/{id}", name="posts_put", methods={"PUT"})
   */
  public function updatePost(Request $request, EntityManagerInterface $entityManager, PlataformaRepository $plataformaRepository, $id){

    $plataforma = $this->getDoctrine()
    ->getRepository(Plataforma::class)
    ->find($id);

     return $this->createApiResponse($plataforma, 200);
 
     if (!$plataforma){
      $plataforma = [
       'status' => 404,
       'errors' => "Plataforma não encontrada pelo id",
      ];
      return $this->createApiResponse($plataforma, 404);
     }
 
      $request = $this->transformJsonBody($request);
 
     if (!$request || !$request->get('name') || !$request->request->get('description')){
      throw new \Exception();
     }
 
     $plataforma->setName($request->get('name'));
     $plataforma->setDescription($request->get('description'));
     $entityManager->flush();
 
     $data = [
      'status' => 200,
      'errors' => "Post updated successfully",
     ];
     
     return $this->createApiResponse($data, 404);

 
   }    


    /**
   * @Route("/plataforma/{id}", name="plataforma_delete", methods={"DELETE"})
   */
  public function deletePost(EntityManagerInterface $entityManager, PlataformaRepository $plataformaRepository, $id){
    $plataforma = $plataformaRepository->find($id);
 
    if (!$plataforma){
     $data = [
      'status' => 404,
      'errors' => "Post not found",
     ];
     return $this->createApiResponse($data, 404);
    }
 
    $entityManager->remove($plataforma);
    $entityManager->flush();
    $data = [
     'status' => 200,
     'errors' => "Post deleted successfully",
    ];
    return $this->createApiResponse($data, 404);
   }


/**
   * Returns a JSON response
   *
   * @param array $data
   * @param $status
   * @param array $headers
   * @return JsonResponse
   */
  public function response($data, $status = 200, $headers = [])
  {
   return new JsonResponse($data, $status, $headers);
  }

  protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
  {
   $data = json_decode($request->getContent(), true);

   if ($data === null) {
    return $request;
   }

   $request->request->replace($data);

   return $request;
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