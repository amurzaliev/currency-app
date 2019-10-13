<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurrencyRateControllerTest extends WebTestCase
{
    public function testConversion()
    {
        $value = 10;
        $client = static::createClient();

        $client->request('GET', 'http://localhost:8000/api/convert', [
            'from' => 'eur',
            'to' => 'rub',
            'value' => $value
        ]);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($value, $response['value']);
    }
}