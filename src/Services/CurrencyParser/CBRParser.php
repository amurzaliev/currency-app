<?php

namespace App\Services\CurrencyParser;

use DOMElement;
use Exception;
use Symfony\Component\DomCrawler\Crawler;

class CBRParser extends AbstractCurrencyParser
{
    const URL = 'https://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * Parse CBR source for current date
     *
     * @return array|null
     * @throws Exception
     */
    function parse(): ?array
    {
        $result = [];
        $crawler = new Crawler(file_get_contents(self::URL));
        dump($crawler->count());
        $date = new \DateTime($crawler->filterXPath('//ValCurs/@Date')->text());
        $rates = $crawler->filterXPath('//ValCurs/Valute');

        /** @var DOMElement $node */
        foreach ($rates as $node) {
            $result[] = [
                'code' => $node->childNodes[1]->nodeValue,
                'rate' => (float)str_replace(',', '.', $node->childNodes[4]->nodeValue),
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
            'code' => 'RUB',
            'rate' => 1 / $denominator,
            'date' => $normalizedData[0]['date'],
        ];

        return $normalizedData;
    }
}