<?php


namespace App\Controller\App;


use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Uloc\ApiBundle\Controller\BaseController;
use Uloc\ApiBundle\Manager\UserManagerInterface;
use Uloc\ApiBundle\Controller\Api\TokenController as TokenProxy;

class TokenController extends BaseController
{
    /**
     * @Route("/api/auth", name="uloc_token_new")
     * @throws \Exception
     */
    public function newToken(Request $request, UserManagerInterface $userManager)
    {
        $proxy = new TokenProxy($this->logger, $this->errorHandler);
        TokenProxy::$AuthHeaders[] = ['key' => 'Access-Control-Allow-Credentials', 'value' => 'true'];

        $getExpires = function ($token) {
            $token = explode('.', $token);
            $session = json_decode(base64_decode($token[1]), true);
            return $session['exp'];
        };

        TokenProxy::$AuthCookies[] = function ($data) use ($getExpires) {
            return Cookie::create('sl_session')
                ->withValue($data['token'])
                ->withExpires($getExpires($data['token']))
                ->withSecure(true)
                ->withSameSite('None');
                # ->withSecure(false); //TODO: Only local to test. Prod use wss and receiver cookie with security=true
                // ->withSameSite('');
        };

        TokenProxy::$AuthCookies[] = function ($data) use ($getExpires) {
            return Cookie::create('sl_session-token')
                ->withValue($data['token'])
                ->withExpires($getExpires($data['token']))
                ->withSecure(true)
                ->withSameSite('None');
                #->withSecure(false); //TODO: Only local to test. Prod use wss and receiver cookie with security=true
                // ->withSameSite('');
        };

        TokenProxy::$AuthCookies[] = function ($data) use ($getExpires) {
            return Cookie::create('sl_session-person')
                ->withValue($data['user']['name'])
                ->withExpires($getExpires($data['token']))
                ->withSecure(true)
                ->withSameSite('None');
                #->withSecure(false); //TODO: Only local to test. Prod use wss and receiver cookie with security=true
                // ->withSameSite('');
        };

        TokenProxy::$AuthCookies[] = function ($data) use ($getExpires) {
            return Cookie::create('sl_session-username')
                ->withValue($data['user']['username'])
                ->withExpires($getExpires($data['token']))
                ->withSecure(true)
                ->withSameSite('None');
                #->withSecure(false); //TODO: Only local to test. Prod use wss and receiver cookie with security=true
                // ->withSameSite('');
        };

        TokenProxy::$AuthCookies[] = function ($data) use ($getExpires) {
            return Cookie::create('sl_session-client')
                ->withValue($_SERVER['SL_CLIENT'])
                ->withExpires($getExpires($data['token']))
                ->withSecure(true)
                ->withSameSite('None');
                #->withSecure(false); //TODO: Only local to test. Prod use wss and receiver cookie with security=true
            // ->withSameSite('');
        };

        TokenProxy::$AuthCookies[] = function ($data) use ($getExpires) {
            return Cookie::create('sl_session-userData')
                ->withValue(implode(';', [
                    $data['user']['id'],
                    $data['user']['username'],
                    $data['user']['person'],
                    $data['user']['name'],
                ]))
                ->withExpires($getExpires($data['token']))
                ->withSecure(true)
                ->withSameSite('None');
                #->withSecure(false); //TODO: Only local to test. Prod use wss and receiver cookie with security=true
            // ->withSameSite('');
        };

        return $proxy->newToken($request, $userManager);

    }

    /**
     * @Route("/api/userCredentials", name="uloc_token_user_credentials")
     * @throws \Exception
     */
    public function userCredentials(Request $request)
    {
        $proxy = new TokenProxy($this->logger, $this->errorHandler);
        return $proxy->userCredentials($request);
    }
}
