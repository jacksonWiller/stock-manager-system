<?php

namespace Tests\UlocApiBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Uloc\ApiBundle\Manager\UserManager;
use Uloc\ApiBundle\Test\ApiTestCase;

class UserManagerTest extends ApiTestCase
{
    public static $userCreated = false;
    public static $user;

    public static $userSample = 'tiago.userManagerTest';

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$userCreated) {
            self::$user = $this->createUser(self::$userSample);
            self::$userCreated = true;
        }
    }

    public function testNewUser()
    {

        $data = array(
            'name' => 'Tiago Felipe',
            'username' => 'tiago2',
            'email' => 'tiago@tiagofelipe.com',
            'password' => '1234',
            'roles' => ['ROLE_TEAM_MEMBERS__2'], // TODO: Save in create user or second endpoint ?
            'acl' => ['acl/test/view']
        );

        $response = $this->client->post('/api/users', [
            'body' => json_encode($data),
            'headers' => $this->getAuthorizedHeaders(self::$userSample)
        ]);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUniqueUsername()
    {

        $data = array(
            'name' => 'Tiago Felipe',
            'username' => 'tiago2',
            'email' => 'tiago@tiagofelipe.com',
            'password' => '1234'
        );

        $response = $this->client->post('/api/users', [
            'body' => json_encode($data),
            'headers' => $this->getAuthorizedHeaders(self::$userSample)
        ]);

        $body = json_decode($response->getBody(true), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', $body);
    }

    public function testListUsers()
    {
        $response = $this->client->get('/api/users?page=1&limit=2', [
            'headers' => $this->getAuthorizedHeaders(self::$userSample)
        ]);

        $body = json_decode($response->getBody(true), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyIsArray($response, 'result');
        $this->assertArrayHasKey('total', $body);
    }

    public function testShowUser()
    {

        $response = $this->client->get('/api/users/' . self::$user->getId(), [
            'headers' => $this->getAuthorizedHeaders(self::$userSample)
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyEquals(
            $response,
            'username',
            self::$userSample
        );
    }

    public function testShowUserAccessDenied()
    {

        $response = $this->client->get('/api/users/' . (intval(self::$user->getId()) + 1), [
            'headers' => $this->getAuthorizedHeaders(self::$userSample)
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testShowUserNotFound()
    {

        $response = $this->client->get('/api/users/' . 0, [
            'headers' => $this->getAuthorizedHeaders(self::$userSample)
        ]);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testUpdateUser()
    {

        $data = array(
            'name' => 'Tiago Felipe__updated',
            'username' => 'tiago2__updated',
            'email' => 'tiago@tiagofelipe.com__updated',
            'password' => '1234__updated',
            'roles' => ['ROLE_TEAM_MEMBERS__updated', 'ROLE_ROOT'],
            'acl' => ['acl/test/view__updated', 'acl/is/root']
        );

        $response = $this->client->put('/api/users/' . self::$user->getId(), [
            'body' => json_encode($data),
            'headers' => $this->getAuthorizedHeaders(self::$userSample)
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->client->get('/api/users/' . self::$user->getId(), [
            'headers' => $this->getAuthorizedHeaders(self::$userSample)
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyEquals(
            $response,
            'email',
            'tiago@tiagofelipe.com__updated'
        );
        $body = json_decode($response->getBody(true), true);
        $this->assertFalse(array_search(strtoupper('ROLE_TEAM_MEMBERS__updated'), $body['roles'], true));
        $this->assertFalse(array_search(strtoupper('acl/test/view__updated'), $body['acl'], true));

        // $this->printDebug($response->getBody());
    }

    public function testUpdateUserNotAuthorized()
    {

        $data = array(
            'name' => 'Tiago Felipe__updated2',
            'username' => 'tiago2__updated2',
            'email' => 'tiago@tiagofelipe.com__updated2',
            'password' => '1234__updated2',
            'roles' => ['ROLE_TEAM_MEMBERS__updated2', 'ROLE_ROOT'],
            'acl' => ['acl/test/view__updated2', 'acl/is/root']
        );

        $id = (intval(self::$user->getId())+1);
        $response = $this->client->put('/api/users/' . $id, [
            'body' => json_encode($data),
            'headers' => $this->getAuthorizedHeaders(self::$userSample)
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        // Tests whether entity has not been modified
        $user = $this->createUser('boss', 'foo', null, ['ROLE_ADMIN', 'ROLE_API', 'ROLE_TEAM_MEMBERS']);

        $response2 = $this->client->get('/api/users/' . $id, [
            'headers' => $this->getAuthorizedHeaders($user->getUsername()) // ADMIN can make anything
        ]);
        $body = json_decode($response2->getBody(true), true);
        $this->assertNotEquals('tiago@tiagofelipe.com__updated2', $body['email']); // if it is the same, security failed because it could not change.
    }
}

