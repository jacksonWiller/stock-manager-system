<?php


namespace App\Controller;


use App\Entity\App\FileModel;
use App\Services\Image\ImageService;
use App\Services\Uploader\FileUploader;
use Symfony\Component\HttpFoundation\File\File as FileObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ControllerTrait
{

    // TODO: Trocar pelo uploadFile
    public function uploadEntityImage($entity, $namespace, Request $request, FileUploader $fileUploader, ValidatorInterface $validator, SerializerInterface $serializer, &$imageData = null)
    {
        /** @var FileModel $uploadApiModel */
        $uploadApiModel = $serializer->deserialize(
            $request->getContent(),
            FileModel::class,
            'json'
        );

        $violations = $validator->validate($uploadApiModel);
        if ($violations->count() > 0) {
            // return $this->json($violations, 400);
            throw new \Exception(serialize(['error' => 'validation', 'message' => $this->violationsToArray($violations)]));
        }

        $tmpName = 'sl-c-' . $entity->getId() . '--' . uniqid();
        $tmpPath = sys_get_temp_dir() . '/' . $tmpName;
        file_put_contents($tmpPath, $uploadApiModel->getDecodedData());
        $uploadedFile = new FileObject($tmpPath);
        $originalFilename = $uploadApiModel->filename;
        $mimeType = $uploadedFile->getMimeType() ?? 'application/octet-stream';

        $violations = $validator->validate(
            $uploadedFile,
            [
                new NotBlank([
                    'message' => 'Por favor selecione um arquivo para upload'
                ]),
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/*'
                    ]
                ])
            ]
        );

        if ($violations->count() > 0) {
            // return $this->json($violations, 400);
            throw new \Exception(serialize(['error' => 'validation', 'message' => $this->violationsToArray($violations)]));
        }

        $filename = $fileUploader->uploadFile($uploadedFile, $namespace, true);

        if (is_file($uploadedFile->getPathname())) {
            unlink($uploadedFile->getPathname());
        }

        $imageUrl = implode("/", [$fileUploader->getPublicUrl(), $namespace, $filename]);
        $imageData = [
            'fileName' => $filename,
            'originalFilename' => $originalFilename,
            'url' => $imageUrl,
            'mimeType' => $mimeType
        ];

        return $imageUrl;
    }


    public function uploadFile($name, $namespace, Request $request, FileUploader $fileUploader, ValidatorInterface $validator, SerializerInterface $serializer, $arrValidation = null, $public = true, $preUploadCallback = null, $postUploadCallback = null, $uploadedFile = null)
    {
        set_time_limit(0);
        // ini_set('memory_limit', -1);
        if (null === $uploadedFile) {
            /** @var FileModel $uploadApiModel */
            $uploadApiModel = $serializer->deserialize(
                $request->getContent(),
                FileModel::class,
                'json'
            );

            $violations = $validator->validate($uploadApiModel);
            if ($violations->count() > 0) {
                // return $this->json($violations, 400);
                throw new \Exception(serialize(['error' => 'validation', 'message' => $this->violationsToArray($violations)]));
            }


            //if (null === $file) {
            $tmpPath = sys_get_temp_dir() . '/' . $name;
            //} else {
            //    $tmpPath = $file;
            //}
            file_put_contents($tmpPath, $uploadApiModel->getDecodedData());
            $originalFilename = $uploadApiModel->filename;
            $uploadedFile = new FileObject($tmpPath);
        } else {
            $originalFilename = $uploadedFile->getClientOriginalName();
        }
        $mimeType = $uploadedFile->getMimeType() ?? 'application/octet-stream';

        if (null === $arrValidation) {
            $arrValidation = [
                new NotBlank([
                    'message' => 'Please select a file to upload'
                ]),
                new File([
                    'maxSize' => '100M'
                ])
            ];
        }
        $violations = $validator->validate(
            $uploadedFile,
            $arrValidation
        );

        if ($violations->count() > 0) {
            throw new \Exception(serialize(['error' => 'validation', 'message' => $this->violationsToArray($violations)]));
        }

        if (is_callable($preUploadCallback)) {
            /**
             * Callback. Parse
             * $tmpFile
             */
            $uploadedFile = new FileObject($preUploadCallback($tmpPath));
        }
        $extras = [];
        $filename = $fileUploader->uploadFile($uploadedFile, $namespace, $public, $extras);

        $postUploadCallbackResponse = null;
        if (is_callable($postUploadCallback)) {
            /**
             * Callback. Parse
             * $tmpFile
             * $uploadedFile
             */
            $postUploadCallbackResponse = $postUploadCallback($tmpPath, $filename);
        }
        if (is_file($uploadedFile->getPathname())) {
            unlink($uploadedFile->getPathname());
        }

        $resolution = isset($extras['width']) && isset($extras['height']) ? ['width' => $extras['width'], 'height' => $extras['height']] : null;

        $imageUrl = implode("/", [$fileUploader->getPublicUrl(), $namespace, $filename]);
        $imageData = [
            'fileName' => $filename,
            'originalFilename' => $originalFilename,
            'url' => $imageUrl,
            'mimeType' => $mimeType,
            'resolution' => $resolution
        ];

        if (is_array($postUploadCallbackResponse)) {
            $imageData = array_merge_recursive($imageData, $postUploadCallbackResponse);
        }

        return $imageData;
    }

    public function violationsToArray($violations)
    {
        $arr = [];
        foreach ($violations as $violation) {
            $arr[] = $violation->getMessage();
        }
        return $arr;
    }

    /**
     * @param $tmpFile
     * @param $uploadedFile
     * @param ImageService $imageService
     * @param FileUploader $fileUploader
     * @param $namespace
     * @param $versions
     * @throws \Exception
     */
    function postUploadCallback($tmpFile, $uploadedFile, $imageService, $fileUploader, $namespace, &$versions)
    {
        if (false !== $imageService->isImage($tmpFile)) {
            // is image
            $thumb = $imageService->resize($tmpFile, 250);
            $extrasThumb = [];
            $fileThumb = $fileUploader->uploadFile(new FileObject($thumb), $namespace, true, $extrasThumb);
            $versions['thumb'] = [
                'url' => implode("/", [$fileUploader->getPublicUrl(), $namespace, $fileThumb]),
                'resolution' => isset($extrasThumb['width']) && isset($extrasThumb['height']) ? ['width' => $extrasThumb['width'], 'height' => $extrasThumb['height']] : null
            ]; // TODO: Falha de segurança. Determinados arquivos, mesmo thumb, não pode ser publico

            $min = $imageService->resize($tmpFile, 650);
            $extrasMin = [];
            $fileMin = $fileUploader->uploadFile(new FileObject($min), $namespace, true, $extrasMin);
            $versions['min'] = [
                'url' => implode("/", [$fileUploader->getPublicUrl(), $namespace, $fileMin]),
                'resolution' => isset($extrasMin['width']) && isset($extrasMin['height']) ? ['width' => $extrasMin['width'], 'height' => $extrasMin['height']] : null
            ]; // TODO: Falha de segurança. Determinados arquivos, mesmo thumb, não pode ser publico

            if (is_file($thumb)) {
                unlink($thumb);
            }
            if (is_file($min)) {
                unlink($min);
            }
        }
    }

    /**
     * @param $tmpFile
     * @param ImageService $imageService
     * @param FileUploader $fileUploader
     * @param $namespace
     * @param int $max
     * @return mixed
     */
    function preUploadCallback($tmpFile, $imageService, $fileUploader, $namespace = '', $max = 1920)
    {
        if (false !== $imageService->isImage($tmpFile)) {
            // is image
            // Check limit FULL HD image
            // $max = 1920;
            list($width, $height) = $imageService->getImageSize($tmpFile);
            if ($width > $max || $height > $max) {
                if ($width > $height) {
                    $tmpFile = $imageService->resize($tmpFile, $max);
                } else {
                    $tmpFile = $imageService->resize($tmpFile, 0, $max);
                }
            }
            return $tmpFile;
        }
        return $tmpFile;
    }

}