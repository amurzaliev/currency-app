<?php

namespace App\Services\CurrencyParser;

use DOMElement;
use Exception;
use Symfony\Component\DomCrawler\Crawler;

class ECBParser extends AbstractCurrencyParser
{
    const URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    /**
     * Parse ECB source for current date
     *
     * @return array|null
     * @throws Exception
     */
    function parse(): ?array
    {
        $result = [];
        $crawler = new Crawler(file_get_contents(self::URL));
        $date = new \DateTime($crawler->filterXPath('//default:Cube/default:Cube/@time')->text());
        $rates = $crawler->filterXPath('//default:Cube/default:Cube/default:Cube');

        /** @var DOMElement $node */
        foreach ($rates as $node) {
            $result[] = [
                'code' => $node->attributes[0]->nodeValue,
                'rate' => (float)str_replace(',', '.', $node->attributes[1]->nodeValue),
                'date' => $date,
            ];
        }

        return $result;
    }

    /**
     * Normalize data to USD
     *
     * @param array $data
     * @return array|null
     */
    function normalize(array $data): ?array
    {
        $denominator = null;
        foreach ($data as $key => $item) {
            if ($item['code'] === 'USD') {
                $denominator = $data[$key]['rate'];
            }
        }

        if (!$denominator) {
            return null;
        }

        $normalizedData = [];

        foreach ($data as $item) {
            $item['rate'] = $item['rate'] / $denominator;
            $normalizedData[] = $item;
        }

        $normalizedData[] = [
            'code' => 'EUR',
            'rate' => 1 / $denominator,
            'date' => $normalizedData[0]['date'],
        ];

        return $normalizedData;
    }
}