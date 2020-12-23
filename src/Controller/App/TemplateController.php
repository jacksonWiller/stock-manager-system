<?php

namespace App\Controller\App;

use App\Entity\App\TemplateCategoria;
use App\Form\App\TemplateType;
use App\Repository\App\TemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Uloc\ApiBundle\Controller\BaseController;
use App\Entity\App\Template;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadata;

class TemplateController extends BaseController
{

    /**
     * @Route(name="template_create", methods={"POST"}, path="/api/templates")
     */
    public function newTemplate(Request $request)
    {
        try {
            $this->isGrantedAcl('uloc/templates/create');
            $template = new Template();
            $this->processTemplateForm($template, $request);

            $em = $this->getDoctrine()->getManager();
            $em->persist($template);
            $em->flush();

            return $this->createApiResponse($template, 201);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="template_list", methods={"GET"}, path="/api/templates")
     */
    public function listTemplate(TemplateRepository $templateRepository, Request $request)
    {
        try {
            $this->isGrantedAcl('uloc/templates/list');

            list($page, $limit, $offset) = $this->getPagination($request, 100, self::MAX_RESULT_LIMIT);
            $filters = [];

            $this->bindFormEntityFilters($request, $filters);

            $templates = $templateRepository->findAllSimple(
                $limit,
                $offset,
                $request->query->get('sortBy'),
                $request->query->get('descending'),
                $filters,
                null,
                true
            ); // $manager->list($limit, $offset, ['role' => '']);
            // dump($templates);
            $templates['result'] = $this->serialize($templates['result'], 'array', 'admin', function (ApiRepresentationMetadata $metadata) {
                Template::loadApiRepresentation($metadata);
            });

            $internals = new ArrayCollection();
            $customs = new ArrayCollection();

            foreach ($templates['result'] as $template) {
                if ($template['interno']) {
                    $internals->add($template);
                } else {
                    $customs->add($template);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $categories = $em->getRepository(TemplateCategoria::class)->findAll();

            return $this->createApiResponseEncodeArray([
                'internals' => [
                    'result' => $internals->toArray(),
                    'total' => $internals->count(),
                ],
                'customs' => [
                    'result' => $customs->toArray(),
                    'total' => $customs->count(),
                ],
                'categories' => $this->serialize($categories, 'array')
            ], 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="template_show", methods={"GET"}, path="/api/templates/{id}")
     */
    public function showTemplate(Template $template, Request $request)
    {
        try {
            $this->isGrantedAcl('uloc/templates/show', $template->getId());

            return $this->createApiResponse($template, 200, null, 'edit');
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="template_findByCodigo", methods={"GET"}, path="/api/templates/findByCodigo/{codigo}")
     */
    public function findByCodigo($codigo, Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $template = $em->getRepository(Template::class)->findOneBy(['default' => true, 'codigo' => $codigo], ['id' => 'ASC']);
            if (!$template) {
                throw new \Exception('Nenhum templaste encontrado');
            }
            // $this->isGrantedAcl('uloc/templates/show', $template->getId());

            return $this->createApiResponse($template, 200, null, 'edit');
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="template_update", methods={"PUT", "PATCH"}, path="/api/templates/{id}")
     */
    public function updateTemplate(Template $template, Request $request)
    {
        try {
            // $original = clone $template;

            $acl = 'uloc/templates/update';
            $this->isGrantedAcl($acl);
            $this->processTemplateForm($template, $request, true);

            $em = $this->getDoctrine()->getManager();

            $em->persist($template);
            $em->flush();

            return $this->createApiResponse($template, 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="template_delete", methods={"DELETE"}, path="/api/templates/{id}")
     */
    public function deleteTemplate(Template $template, Request $request)
    {
        try {
            $acl = 'uloc/templates/delete';
            $this->isGrantedAcl($acl);

            $em = $this->getDoctrine()->getManager();

            $em->remove($template);
            $em->flush();

            return $this->createApiResponse(['status' => 'DELETED'], 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @param Template $template
     * @param Request $request
     * @throws \Exception
     */
    protected function processTemplateForm(Template $template, Request $request, $update = false)
    {
        $form = $this->createForm(TemplateType::class, $template);
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            throw new \Exception(serialize(['error' => 'validation', 'message' => $this->getErrorsFromForm($form)]));
        }
    }

}