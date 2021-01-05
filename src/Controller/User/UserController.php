<?php

namespace App\Controller\User;

use App\Controller\ControllerTrait;
use App\Entity\Afiliado;
use App\Entity\AfiliadoUsuario;
use App\Manager\PlataformaManager;
use App\Services\Image\ImageService;
use App\Services\Uploader\FileUploader;
use Proxies\__CG__\Uloc\ApiBundle\Entity\Person\Person;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Uloc\ApiBundle\Controller\BaseController;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Form\UserType;
use Uloc\ApiBundle\Manager\UserManagerInterface;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadata;

class UserController extends BaseController
{

    use ControllerTrait;

    /**
     * @Route(name="user_create", methods={"POST"}, path="/api/users")
     */
    public function newUser(Request $request, UserManagerInterface $manager)
    {
        try {
            $this->isGrantedAcl('uloc/user/create');
            $user = new User();
            $this->processUserForm($user, $request);

            /* @var User $user */
            $user = $manager->create($user->getName(), $user->getUsername(), $user->getEmail(), $user->getPassword(), true, ['roles' => $user->getRoles(), 'acl' => $user->getAcl()]);
            return $this->createApiResponseEncodeArray(['id' => $user->getId(), 'password' => $user->getPlainPassword()], 201);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="user_list", methods={"GET"}, path="/api/users")
     */
    public function listUser(Request $request, UserManagerInterface $manager)
    {
        try {
            $this->isGrantedAcl('uloc/user/list');

            list($page, $limit, $offset) = $this->getPagination($request, 100, self::MAX_RESULT_LIMIT);

            $users = $manager->list($limit, $offset, ['role' => 'ROLE_USER']);
            $users['result'] = $this->serialize($users['result'], 'array', 'admin', function (ApiRepresentationMetadata $metadata) {
                User::loadApiRepresentation($metadata);
            });
            return $this->createApiResponseEncodeArray($users, 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="user_show", methods={"GET"}, path="/api/users/{id}")
     */
    public function showUser($id, Request $request, UserManagerInterface $manager)
    {
        //try {
        $user = $manager->find($id);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        $this->isGrantedAcl('uloc/user/show', $user->getId());
        $manager->manager($user);

        return $this->createApiResponse($user, 200, null, 'admin');
        //} catch (\Exception $e) {
        //    return $this->errorHandler->handlerError($e->getMessage());
        //}
    }

    /**
     * @Route(name="user_update", methods={"PUT", "PATCH"}, path="/api/users/{id}")
     */
    public function updateUser($id, Request $request, UserManagerInterface $manager)
    {
        try {
            $user = $manager->find($id);
            if (!$user) {
                throw $this->createNotFoundException();
            }
            $original = clone $user;
            // $user->setAcl([]);
            $acl = 'uloc/user/update';
            $this->isGrantedAcl($acl, $user->getId());
            $manager->manager($user);
            $this->processUserForm($user, $request, true);

            if (empty($user->getPassword())) {
                $user->setPassword($original->getPassword());
            }

            if (!$this->checkAcl($acl)) {
                // User editing youself, but some fields can modified only by admin or who contains acl
                $user->setUsername($original->getUsername());
                $user->setAcl($original->getAcl());
                $user->setRoles($original->getRoles());
            }

            $manager->update();
            return $this->createApiResponse($user, 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="user_reset_password", methods={"GET"}, path="/api/users/{id}/resetPassword")
     */
    public function resetUserPassword($id, Request $request, UserManagerInterface $manager)
    {
        try {
            $user = $manager->find($id);
            if (!$user) {
                throw $this->createNotFoundException();
            }
            $acl = 'uloc/user/update';
            $this->isGrantedAcl($acl, $user->getId());
            $manager->manager($user);
            $password = $manager->redefinePassword();
            return $this->createApiResponseEncodeArray(['id' => $user->getId(), 'password' => $password], 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="user_update_password", methods={"PUT"}, path="/api/users/{id}/updatePassword")
     */
    public function updateUserPassword($id, Request $request, UserManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        try {
            $user = $manager->find($id);
            if (!$user) {
                throw $this->createNotFoundException();
            }
            $acl = 'uloc/user/update';
            $this->isGrantedAcl($acl, $user->getId());
            $manager->manager($user);

            $data = \json_decode($request->getContent(), true);

            if (empty($data['senhaAtual'])) {
                throw new \Exception('Necessário confirmar a senha atual', JsonResponse::HTTP_BAD_REQUEST);
            }

            if (empty($data['novaSenha'])) {
                throw new \Exception('Senha não pode ser vazia', JsonResponse::HTTP_BAD_REQUEST);
            }

            if (!$manager->isPasswordValid($data['senhaAtual'])) {
                throw new \Exception('Senha atual inválida', JsonResponse::HTTP_BAD_REQUEST);
            }

            $password = $passwordEncoder->encodePassword($user, $data['novaSenha']);

            $user->setPassword($password);

            $manager->update();

            return $this->createApiResponseEncodeArray(['id' => $user->getId(), 'password' => $password], 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @Route(name="update_user_profile_picture", methods={"PUT"}, path="/api/users/{id}/updateProfilePicture")
     *
     * @param Request $request
     * @param User $user
     * @param FileUploader $fileUploader
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param ImageService $imageService
     * @return mixed
     */
    public function uploadProfilePicture(Request $request, User $user, FileUploader $fileUploader, ValidatorInterface $validator, SerializerInterface $serializer, ImageService $imageService)
    {

        try {
            $acl = 'uloc/user/update';
            $this->isGrantedAcl($acl, $user->getId());

            $preUploadCallback = function ($tmpFile) use ($imageService, $fileUploader) {
                return $this->preUploadCallback($tmpFile, $imageService, $fileUploader, '', 800);
            };

            $filename = 'sl-usr-' . $user->getId() . '--' . uniqid();

            $fileInfo = $this->uploadFile(
                $filename,
                FileUploader::getNamespace(FileUploader::USER),
                $request,
                $fileUploader,
                $validator,
                $serializer,
                null,
                true,
                $preUploadCallback,
            // $postUploadCallback
            );

            $em = $this->getDoctrine()->getManager();

            $user->getPerson()->setPhoto($fileInfo['url']);
            $em->flush();

            return $this->createApiResponse(
                array(
                    'image' => $user->getPerson()->getPhoto()
                ),
                200,
                null,
                'admin'
            );

        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

    /**
     * @param User $user
     * @param Request $request
     * @throws \Exception
     */
    protected function processUserForm(User $user, Request $request, $update = false)
    {
        $form = $this->createForm(UserType::class, $user);
        if ($update) {
            $form->add('active');
        }
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            throw new \Exception(serialize(['error' => 'validation', 'message' => $this->getErrorsFromForm($form)]));
        }
    }


}
