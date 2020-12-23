<?php

namespace Tests\UlocApiBundle\Controller\Api;

use Uloc\ApiBundle\Test\ApiTestCase;

class TokenControllerTest extends ApiTestCase
{
    public function testPOSTCreateToken()
    {
        $this->createUser('tiago', 'I<3Enduro');

        $response = $this->client->post('/api/auth', [
            'form_params' => [
                'user' => 'tiago',
                'pass' => 'I<3Enduro'
            ]
        ]);

        // var_dump($response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyExists(
            $response,
            'token'
        );
    }

    public function testPOSTTokenInvalidCredentials()
    {
        // $this->createUser('tiago', 'I<3Enduro');

        $response = $this->client->post('/api/auth', [
            'user' => 'tiago',
            'pass' => 'Inot<3Enduro'
        ]);
        $this->assertEquals(401, $response->getStatusCode());
    }
}
