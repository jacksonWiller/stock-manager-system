<?php


namespace App\Tests\AppBundle\Pessoa;


use Uloc\ApiBundle\Entity\Person\TypePersonDocument;

trait PessoaTrait
{
    public static $tiposDocumentoPessoa = [];
    public static $tiposEnderecoPessoa = [];
    public static $tiposEmailPessoa = [];
    public static $tiposTelefonePessoa = [];
    public static $tiposContatosPessoa = [];

    public function criaTiposDocumentoPessoa()
    {
        $data = [
            [
                'name' => 'RG',
                'code' => TypePersonDocument::TIPO_BRAZIL_RG,
                'active' => true,
            ],
            [
                'name' => 'Passaporte',
                'code' => TypePersonDocument::TIPO_PASSAPORTE,
                'active' => true,
            ]
        ];

        foreach ($data as $tipo) {
            $response = $this->client->post('/api/tiposDocumento', [
                'body' => json_encode($tipo),
                'headers' => $this->getAuthorizedHeaders(self::$userSample)
            ]);

            $body = json_decode($response->getBody(true), true);
            self::$tiposDocumentoPessoa[] = $body['id'];
        }
    }

    public function criaTiposEndereco()
    {
        $data = [
            [
                'name' => 'Residencial',
                'code' => 'residencial',
                'active' => true,
            ],
            [
                'name' => 'Comercial',
                'code' => 'comercial',
                'active' => true,
            ]
        ];

        foreach ($data as $tipo) {
            $response = $this->client->post('/api/tiposEndereco', [
                'body' => json_encode($tipo),
                'headers' => $this->getAuthorizedHeaders(self::$userSample)
            ]);

            $body = json_decode($response->getBody(true), true);
            self::$tiposEnderecoPessoa[] = $body['id'];
        }
    }

    public function criaTiposEmail()
    {
        $data = [
            [
                'name' => 'Pessoal',
                'code' => 'pessoal',
                'active' => true,
            ],
            [
                'name' => 'Comercial',
                'code' => 'comercial',
                'active' => true,
            ]
        ];

        foreach ($data as $tipo) {
            $response = $this->client->post('/api/tiposEmail', [
                'body' => json_encode($tipo),
                'headers' => $this->getAuthorizedHeaders(self::$userSample)
            ]);

            $body = json_decode($response->getBody(true), true);
            self::$tiposEmailPessoa[] = $body['id'];
        }
    }

    public function criaTiposTelefone()
    {
        $data = [
            [
                'name' => 'Pessoal',
                'code' => 'pessoal',
                'active' => true,
            ],
            [
                'name' => 'Comercial',
                'code' => 'comercial',
                'active' => true,
            ]
        ];

        foreach ($data as $tipo) {
            $response = $this->client->post('/api/tiposTelefone', [
                'body' => json_encode($tipo),
                'headers' => $this->getAuthorizedHeaders(self::$userSample)
            ]);

            $body = json_decode($response->getBody(true), true);
            self::$tiposTelefonePessoa[] = $body['id'];
        }
    }

    public function criaTiposContato()
    {
        $data = [
            [
                'name' => 'Facebook',
                'code' => 'facebook',
                'active' => true,
            ],
            [
                'name' => 'Instagram',
                'code' => 'instagram',
                'active' => true,
            ]
        ];

        foreach ($data as $tipo) {
            $response = $this->client->post('/api/tiposContato', [
                'body' => json_encode($tipo),
                'headers' => $this->getAuthorizedHeaders(self::$userSample)
            ]);

            $body = json_decode($response->getBody(true), true);
            self::$tiposContatosPessoa[] = $body['id'];
        }
    }
}