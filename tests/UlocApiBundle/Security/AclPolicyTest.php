<?php

namespace Tests\UlocApiBundle\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Uloc\ApiBundle\Manager\UserManager;
use Uloc\ApiBundle\Test\ApiTestCase;

class AclPolicyTest extends ApiTestCase
{
    public static $user = null;

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$user) {
            // self::$user = $this->createUser('tiago'); //
        }
    }

    public function testNewUserWithValidAcl()
    {

        $this->createUser('testacl1', '1234', ['uloc/user/create']);

        $data = array(
            'name' => 'Tiago Felipe',
            'username' => 'testacl1_create',
            'email' => 'tiago@tiagofelipe.com',
            'password' => '1234'
        );

        $response = $this->client->post('/api/users', [
            'body' => json_encode($data),
            'headers' => $this->getAuthorizedHeaders('testacl1')
        ]);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testNewUserWithInvalidAcl()
    {

        $this->createUser('testacl2', '1234', ['uloc/person/create']);

        $data = array(
            'name' => 'Tiago Felipe',
            'username' => 'testacl2_create',
            'email' => 'tiago@tiagofelipe.com',
            'password' => '1234'
        );

        $response = $this->client->post('/api/users', [
            'body' => json_encode($data),
            'headers' => $this->getAuthorizedHeaders('testacl2')
        ]);

        $body = json_decode($response->getBody(true), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', $body);
        $this->assertEquals('Access Denied', @$body['message']);
    }

    public function testNewUserWithValidAclLevel0()
    {

        $this->createUser('testacl3', '1234', ['uloc']);

        $data = array(
            'name' => 'Tiago Felipe',
            'username' => 'testacl3_create',
            'email' => 'tiago@tiagofelipe.com',
            'password' => '1234'
        );

        $response = $this->client->post('/api/users', [
            'body' => json_encode($data),
            'headers' => $this->getAuthorizedHeaders('testacl3')
        ]);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testNewUserWithValidAclLevel1()
    {

        $this->createUser('testacl4', '1234', ['uloc/user']);

        $data = array(
            'name' => 'Tiago Felipe',
            'username' => 'testacl4_create',
            'email' => 'tiago@tiagofelipe.com',
            'password' => '1234'
        );

        $response = $this->client->post('/api/users', [
            'body' => json_encode($data),
            'headers' => $this->getAuthorizedHeaders('testacl4')
        ]);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testNewUserWithInvalidAclLevel0()
    {

        $this->createUser('testacl5', '1235', ['uloc1']);

        $data = array(
            'name' => 'Tiago Felipe',
            'username' => 'testacl5_create',
            'email' => 'tiago@tiagofelipe.com',
            'password' => '1235'
        );

        $response = $this->client->post('/api/users', [
            'body' => json_encode($data),
            'headers' => $this->getAuthorizedHeaders('testacl5')
        ]);

        $body = json_decode($response->getBody(true), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertArrayHasKey('error', $body);
        $this->assertEquals('Access Denied', @$body['message']);
    }

}

