<?php

namespace App\Entity\App;

use Doctrine\ORM\Mapping as ORM;
use Uloc\ApiBundle\Entity\FormEntity;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\App\TemplateRepository")
 */
class Template extends FormEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $codigo;

    /**
     * @ORM\ManyToOne(targetEntity="TemplateCategoria")
     */
    private $categoria;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descricao;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $texto;

    /**
     * @ORM\Column(type="boolean")
     */
    private $interno = false;

    /**
     * @ORM\Column(name="is_default", type="boolean", options={"default": 0})
     */
    private $default = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(?string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInterno()
    {
        return $this->interno;
    }

    /**
     * @param mixed $interno
     */
    public function setInterno($interno): void
    {
        if (null === $interno || !is_bool($interno)) {
            return;
        }
        $this->interno = $interno;
    }

    /**
     * @return mixed
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param mixed $categoria
     */
    public function setCategoria(TemplateCategoria $categoria): void
    {
        $this->categoria = $categoria;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * @param bool $default
     */
    public function setDefault(bool $default): void
    {
        $this->default = $default;
    }

    static $public = [
        'id',
        'codigo',
        'categoria' => ['id', 'nome', 'descricao'],
        'nome',
        'descricao',
        'interno',
        'default'
    ];

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        parent::loadApiRepresentation($representation);

        $public = self::$public;
        $edit = array_merge(self::$public, ['texto']);

        $representation
            ->setGroup('public')->addProperties($public)
            ->setGroup('edit')->addProperties($edit)
            ->setGroup('admin')->addProperties($public)->build();
    }
}
