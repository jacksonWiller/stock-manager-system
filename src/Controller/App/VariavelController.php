<?php

namespace App\Controller\App;

use App\Form\App\VariavelType;
use App\Repository\App\VariavelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Uloc\ApiBundle\Controller\BaseController;
use App\Entity\App\Variavel;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadata;

class VariavelController extends BaseController
{

    /**
     * @Route(name="variavel_create", methods={"POST"}, path="/api/variaveis")
     */
    public function newVariavel(Request $request)
    {
        try {
            $this->isGrantedAcl('uloc/variaveis/create');
            $variavel = new Variavel();
            $this->processVariavelForm($variavel, $request);

            $em = $this->getDoctrine()->getManager();
            $em->persist($variavel);
            $em->flush();

            return $this->createApiResponse($variavel, 201);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="variavel_list", methods={"GET"}, path="/api/variaveis")
     */
    public function listVariavel(VariavelRepository $variavelRepository, Request $request)
    {
        try {
            $this->isGrantedAcl('uloc/variaveis/list');

            list($page, $limit, $offset) = $this->getPagination($request, 100, self::MAX_RESULT_LIMIT);
            $filters = [];

            $this->bindFormEntityFilters($request, $filters);

            $variaveis = $variavelRepository->findAllSimple(
                $limit,
                $offset,
                $request->query->get('sortBy'),
                $request->query->get('descending'),
                $filters,
                null,
                true
            ); // $manager->list($limit, $offset, ['role' => '']);
            // dump($variaveis);
            $variaveis['result'] = $this->serialize($variaveis['result'], 'array', 'admin', function (ApiRepresentationMetadata $metadata) {
                Variavel::loadApiRepresentation($metadata);
            });

            $variaveis['internals'] = Variavel::$internals;

            return $this->createApiResponseEncodeArray($variaveis, 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="variavel_show", methods={"GET"}, path="/api/variaveis/{id}")
     */
    public function showVariavel(Variavel $variavel, Request $request)
    {
        try {
            $this->isGrantedAcl('uloc/variaveis/show', $variavel->getId());

            return $this->createApiResponse($variavel, 200, null, 'admin');
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="variavel_update", methods={"PUT", "PATCH"}, path="/api/variaveis/{id}")
     */
    public function updateVariavel(Variavel $variavel, Request $request)
    {
        try {
            // $original = clone $variavel;

            $acl = 'uloc/variaveis/update';
            $this->isGrantedAcl($acl);
            $this->processVariavelForm($variavel, $request, true);

            $em = $this->getDoctrine()->getManager();

            $em->persist($variavel);
            $em->flush();

            return $this->createApiResponse($variavel, 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="variavel_delete", methods={"DELETE"}, path="/api/variaveis/{id}")
     */
    public function deleteVariavel(Variavel $variavel, Request $request)
    {
        try {
            $acl = 'uloc/variaveis/delete';
            $this->isGrantedAcl($acl);

            $em = $this->getDoctrine()->getManager();

            $em->remove($variavel);
            $em->flush();

            return $this->createApiResponse(['status' => 'DELETED'], 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @param Variavel $variavel
     * @param Request $request
     * @throws \Exception
     */
    protected function processVariavelForm(Variavel $variavel, Request $request, $update = false)
    {
        $form = $this->createForm(VariavelType::class, $variavel);
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            throw new \Exception(serialize(['error' => 'validation', 'message' => $this->getErrorsFromForm($form)]));
        }
    }

}