<?php

namespace App\Services\Reader;


class FileReader implements FileReaderInterface
{

    protected $reader;

    public function reader($filepath)
    {
        if (!file_exists($filepath)) {
            throw new \LogicException('File ' . $filepath . ' not exists');
        }
        $info = pathinfo($filepath);
        $ext = $info['extension'];

        switch ($ext) {
            case 'xlsx':
                $this->reader = new ExcelReader();
                return $this->reader->reader($filepath);
            default:
                throw new \LogicException('Unknow reader for extension ' . $ext);
        }
    }

}