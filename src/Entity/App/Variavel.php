<?php

namespace App\Entity\App;

use Doctrine\ORM\Mapping as ORM;
use Uloc\ApiBundle\Entity\App\Variable;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\App\VariavelRepository")
 */
class Variavel extends Variable
{

    public static $internals = [
        "erp.version" => ['Versão do software ERP. Exemplo: 1.0.3stable']
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    static $public = [
        'id',
        'name',
        'value',
        'description',
        'active',
    ];

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        parent::loadApiRepresentation($representation);

        $public = self::$public;

        $representation
            ->setGroup('public')->addProperties($public)
            ->setGroup('admin')->addProperties($public)->build();
    }
}
