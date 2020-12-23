<?php


namespace App\Services\Reader;


class ExcelReader implements FileReaderInterface
{

    protected $stream;

    public function __construct()
    {
        // \PhpOffice\PhpSpreadsheet\Calculation\Functions::setReturnDateType(\PhpOffice\PhpSpreadsheet\Calculation\Functions::RETURNDATE_PHP_DATETIME_OBJECT);
    }

    public function reader($filepath)
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(false);
        return $this->stream = $reader->load($filepath);
    }
}