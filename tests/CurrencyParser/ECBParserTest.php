<?php

namespace App\Tests\CurrencyParser;

use App\Services\CurrencyParser\ECBParser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ECBParserTest extends WebTestCase
{
    public function testParse()
    {
        $parser = new ECBParser();
        $result = $parser->parse();
        $normalizedData = $parser->normalize($result['rates']);

        $this->assertEquals('EUR', $normalizedData[count($normalizedData) - 1]['code']);
    }
}