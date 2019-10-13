<?php

namespace App\Services\CurrencyParser;

abstract class AbstractCurrencyParser
{
    abstract function parse(): ?array;

    abstract function normalize(array $data): ?array;
}