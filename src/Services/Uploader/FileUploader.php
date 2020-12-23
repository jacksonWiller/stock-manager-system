<?php

namespace App\Services\Uploader;

use App\Services\Image\ImageService;
use App\Utils\Transliterator;
// use Transli;
use League\Flysystem\AdapterInterface;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    private $filesystem;
    private $publicAssetBaseUrl;
    private $imageService;

    const ARREMATANTE = 'arrematantes';
    const REBOQUISTA = 'reboquistas';
    const REBOQUE = 'reboques';
    const VISTORIADOR = 'vistoriadores';
    const COMITENTE = 'comitentes';
    const BEM = 'bens';
    const LEILAO = 'leiloes';
    const USER = 'users';
    const BANNER = 'banners';
    const DOCUMENTO_REQUERIDO_ARREMATANTE = 'docrarrematante';

    public function __construct(FilesystemInterface $uploadsFilesystem, string $uploadedAssetsBaseUrl, ImageService $imageService)
    {
        $this->filesystem = $uploadsFilesystem;
        $this->publicAssetBaseUrl = $uploadedAssetsBaseUrl;
        $this->imageService = $imageService;
    }


    public function readStream(string $path)
    {
        $resource = $this->filesystem->readStream($path);

        if ($resource === false) {
            throw new \Exception(sprintf('Error opening stream for "%s"', $path));
        }

        return $resource;
    }

    public function deleteFile(string $path)
    {
        $result = $this->filesystem->delete($path);

        if ($result === false) {
            throw new \Exception(sprintf('Error deleting "%s"', $path));
        }
    }

    public function uploadFile(File $file, string $directory, bool $isPublic, &$extras = null): string
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }
        $newFilename = Transliterator::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->guessExtension();

        $target = $directory . '/' . $newFilename;

        if (strpos($file->getMimeType(), 'image/') > -1) {
            // is Image
            if (is_array($extras)) {
                list ($w, $h) = $this->imageService->getImageSize($file->getPathname());
                $extras['width'] = $w;
                $extras['height'] = $h;
            }
        }

        $stream = fopen($file->getPathname(), 'r');
        $result = $this->filesystem->writeStream(
            $target,
            $stream,
            [
                'visibility' => $isPublic ? AdapterInterface::VISIBILITY_PUBLIC : AdapterInterface::VISIBILITY_PRIVATE
            ]
        );

        if ($result === false) {
            throw new \Exception(sprintf('Could not write uploaded file "%s"', $newFilename));
        }

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $newFilename;
    }

    public function getPublicUrl()
    {
        return $this->publicAssetBaseUrl;
    }

    public static function getNamespace($namespace)
    {
        if (isset($_SERVER['SL_CLIENT'])) { // TODO: Problema com multisite
            $namespace = $_SERVER['SL_CLIENT'] . '/' . $namespace;
        }
        return $namespace;
    }

}