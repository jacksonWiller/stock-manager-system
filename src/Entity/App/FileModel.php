<?php

namespace App\Entity\App;

use Symfony\Component\Validator\Constraints as Assert;

class FileModel
{
    /**
     * @Assert\NotBlank(message="Nome do arquivo (filename) não pode ser em branco")
     */
    public $filename;

    /**
     * @Assert\NotBlank(message="Necessário passar a imagem em base64 na propriedade 'data'")
     */
    private $data;

    private $decodedData;

    public function setData(?string $data)
    {
        $this->data = $data;
        $data = preg_replace('#^data:[^;]+;base64,#', '', $data);
        $this->decodedData = base64_decode($data);
    }

    public function getDecodedData(): ?string
    {
        return $this->decodedData;
    }
}