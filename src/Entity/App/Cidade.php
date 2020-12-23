<?php

namespace App\Entity\App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\App\CidadeRepository")
 */
class Cidade
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\App\UnidadeFederativa", inversedBy="cidades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $uf;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUf(): ?UnidadeFederativa
    {
        return $this->uf;
    }

    public function setUf(?UnidadeFederativa $uf): self
    {
        $this->uf = $uf;

        return $this;
    }
}
