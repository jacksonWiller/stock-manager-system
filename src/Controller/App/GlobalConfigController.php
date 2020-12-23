<?php

namespace App\Controller\App;

use App\Form\App\GlobalConfigType;
use App\Repository\App\GlobalConfigRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Uloc\ApiBundle\Api\ApiProblem;
use Uloc\ApiBundle\Api\ApiProblemException;
use Uloc\ApiBundle\Controller\BaseController;
use Uloc\ApiBundle\Entity\App\GlobalConfig;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadata;

class GlobalConfigController extends BaseController
{

    /**
     * @Route(name="globalConfig_create", methods={"POST"}, path="/api/globalconfigs")
     */
    public function newGlobalConfig(Request $request)
    {
        try {
            $this->isGrantedAcl('uloc/globalconfigs/create');
            $globalConfig = new GlobalConfig();
            $this->processGlobalConfigForm($globalConfig, $request);

            $em = $this->getDoctrine()->getManager();
            $em->persist($globalConfig);
            $em->flush();

            return $this->createApiResponse($globalConfig, 201);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="globalConfig_list", methods={"GET"}, path="/api/globalconfigs")
     */
    public function listGlobalConfig(GlobalConfigRepository $globalConfigRepository, Request $request)
    {
        try {
            $this->isGrantedAcl('uloc/globalconfigs/list');

            list($page, $limit, $offset) = $this->getPagination($request, 100, self::MAX_RESULT_LIMIT);
            $filters = [];

            $this->bindFormEntityFilters($request, $filters);

            $globalconfigs = $globalConfigRepository->findAllSimple(
                $limit,
                $offset,
                $request->query->get('sortBy'),
                $request->query->get('descending'),
                $filters,
                null,
                true
            ); // $manager->list($limit, $offset, ['role' => '']);
            // dump($globalconfigs);
            $globalconfigs['result'] = $this->serialize($globalconfigs['result'], 'array', 'admin', function (ApiRepresentationMetadata $metadata) {
                $metadata->setGroup('admin')->addProperties([
                    'name',
                    'value',
                    'description',
                    'extra',
                ]);
            });


            return $this->createApiResponseEncodeArray($globalconfigs, 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="globalConfig_show", methods={"GET"}, path="/api/globalconfigs/{id}")
     */
    public function showGlobalConfig(GlobalConfig $globalConfig, Request $request)
    {
        try {
            $this->isGrantedAcl('uloc/globalconfigs/show', $globalConfig->getId());

            return $this->createApiResponse($globalConfig, 200, null, 'admin');
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="globalConfig_update_all", methods={"PUT", "PATCH"}, path="/api/globalconfigs")
     */
    public function updateAllGlobalConfig(Request $request)
    {
        try {
            // $original = clone $globalConfig;

            $acl = 'uloc/globalconfigs/update';
            $this->isGrantedAcl($acl);

            $data = \json_decode($request->getContent(), true);
            if ($data === null) {
                $apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

                throw new ApiProblemException($apiProblem);
            }

            if (!is_array($data)) {
                throw new \Exception('Invalid data');
            }

            $em = $this->getDoctrine()->getManager();
            if (count($data) > 500) {
                throw new \Exception('Tentando me burlar? :/. Já sei quem é você, aguade nossas providências.');
            }

            foreach ($data as $config) {
                $entity = $em->getRepository(GlobalConfig::class)->find($config['name']);
                if (!$entity) {
                    $entity = new GlobalConfig();
                }
                $form = $this->createForm(GlobalConfigType::class, $entity);
                $form->submit($config, false);
                if (!$form->isValid()) {
                    throw new \Exception(serialize(['error' => 'validation', 'message' => $this->getErrorsFromForm($form)]));
                }

                $em->persist($entity);
            }
            $em->flush();

            return $this->createApiResponse(['status' => 'OK'], 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="globalConfig_update", methods={"PUT", "PATCH"}, path="/api/globalconfigs/{id}")
     */
    public function updateGlobalConfig(GlobalConfig $globalConfig, Request $request)
    {
        try {
            // $original = clone $globalConfig;

            $acl = 'uloc/globalconfigs/update';
            $this->isGrantedAcl($acl);
            $this->processGlobalConfigForm($globalConfig, $request, true);

            $em = $this->getDoctrine()->getManager();

            $em->persist($globalConfig);
            $em->flush();

            return $this->createApiResponse($globalConfig, 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="globalConfig_delete", methods={"DELETE"}, path="/api/globalconfigs/{id}")
     */
    public function deleteGlobalConfig(GlobalConfig $globalConfig, Request $request)
    {
        try {
            $acl = 'uloc/globalconfigs/delete';
            $this->isGrantedAcl($acl);

            $em = $this->getDoctrine()->getManager();

            $em->remove($globalConfig);
            $em->flush();

            return $this->createApiResponse(['status' => 'DELETED'], 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @param GlobalConfig $globalConfig
     * @param Request $request
     * @throws \Exception
     */
    protected function processGlobalConfigForm(GlobalConfig $globalConfig, Request $request, $update = false)
    {
        $form = $this->createForm(GlobalConfigType::class, $globalConfig);
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            throw new \Exception(serialize(['error' => 'validation', 'message' => $this->getErrorsFromForm($form)]));
        }
    }

    /**
     * @Route(name="globalConfig_public_show", methods={"POST"}, path="/api/public/globalconfigs")
     */
    public function showPublicGlobalConfigs(Request $request)
    {
        try {
            $data = \json_decode($request->getContent(), true);
            if ($data === null) {
                $apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

                throw new ApiProblemException($apiProblem);
            }

            if (count($data) > 20) {
                throw new \Exception('Tentando me burlar? :/. Já sei quem é você, aguade nossas providências. L-20-I');
            }

            $em = $this->getDoctrine()->getManager();
            $query = $em->createQueryBuilder()
                ->select('c')
                ->from(GlobalConfig::class, 'c')
                ->where('c.name IN (:names)')
                ->andWhere('c.extra LIKE :public')
                ->setParameter('names', $data)
                ->setParameter('public', "%{public: true}%");
            $configs = $query->getQuery()->getArrayResult(); // TODO: Cache?

            $response = [];
            if (count($configs)) {
                foreach ($configs as $config) {
                    $response[$config['name']] = $config;
                }
            }

            return $this->createApiResponseEncodeArray($response, 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

}