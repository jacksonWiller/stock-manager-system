<?php


namespace App\Tests\AppBundle\Converter;


use App\Services\Converter\Helpers\YesNo;
use PHPUnit\Framework\TestCase;

class HelperConverterTest extends TestCase
{

    public function testYesNoHelperConverter()
    {
        $yesCases = [
            's',
            'S',
            'Sim',
            'sim',
            'Y',
            'Yes',
            'yes',
            1,
            '1',
            true
        ];

        $noCases = [
            'n',
            'N',
            'Nao',
            'nao',
            'Não',
            'não',
            'No',
            'no',
            'Not',
            'not',
            false,
            0,
            '0',
            '2',
            2
        ];

        $helper = new YesNo();

        foreach ($yesCases as $case){
            $this->assertTrue($helper->bind($case));
        }

        foreach ($noCases as $case){
            $this->assertFalse($helper->bind($case));
        }
    }

}