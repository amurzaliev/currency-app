<?php

namespace App\Tests\CurrencyParser;

use App\Services\CurrencyParser\CBRParser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CBRParserTest extends WebTestCase
{
    public function testParse()
    {
        $parser = new CBRParser();
        $result = $parser->parse();
        $normalizedData = $parser->normalize($result['rates']);

        $this->assertEquals('RUB', $normalizedData[count($normalizedData) - 1]['code']);
    }
}