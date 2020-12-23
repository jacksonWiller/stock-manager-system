<?php

namespace App\Controller\App;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Uloc\ApiBundle\Controller\BaseController;
use Uloc\ApiBundle\Manager\UserManager;

class AppController extends BaseController
{

    /**
     * @Route(name="home", methods={"GET"}, path="/")
     */
    public function hello(Request $request)
    {
        return $this->createApiResponseEncodeArray(['me' => "hello, I'm Api from Suporte Leilões. I'm here to serve you"], 200);
    }

    /**
     * @Route(name="credentials_web", methods={"GET"}, path="/credentials")
     * @Route(name="credentials_api", methods={"GET"}, path="/api/credentials")
     */
    public function credentials(Request $request, UserManager $userManager)
    {
        if ($this->isGranted('ROLE_API')) {
            $user = $this->getUser();
            $userManager->manager($user);
            return $this->json([
                'user' => $userManager->getUserContent()
            ], 200);
        }
        $session = $request->cookies->get('sl_session-token');
        $response = new JsonResponse([
            'session' => $session
        ]);
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $refer = $request->headers->get('origin');
        if (!empty($refer)) {
            $response->headers->set('Access-Control-Allow-Origin', filter_var($refer, FILTER_SANITIZE_URL));
        }

        return $response;
    }

    /**
     * @Route(name="servertime", methods={"GET"}, path="/api/public/servertime")
     */
    public function servertime(Request $request)
    {
        // $timezone = $leilao->getTimezone(); // TODO: Implementar
        $date = date('Y-m-d H:i:s');
        return $this->createApiResponseEncodeArray(['time' => $date], 200);
    }

}