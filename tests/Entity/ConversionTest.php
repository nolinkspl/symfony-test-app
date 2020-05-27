<?php

namespace App\Tests\Entity;

use App\Entity\Amount;
use App\Entity\Conversion;
use App\Entity\Currency;
use PHPUnit\Framework\TestCase;

class ConversionTest extends TestCase
{

    public function testExecute()
    {
        $conversion = $this->generateConversion();
    }

    private function generateConversion(): Conversion
    {
        $result = new Conversion();
        $result->setRate(0.5);
        $result->setFromAmount($this->generateAmount('USD', 125.125));
        $result->setToAmount($this->generateAmount('EUR', 60));
        $result->setDefaultExpireAt();

        return $result;
    }

    private function generateAmount(string $currencyCode, float $amount): Amount
    {
        $result = new Amount();
        $result->setAmount($amount);
        $result->setCurrency($this->generateCurrency($currencyCode));

        return $result;
    }

    private function generateCurrency(string $code): Currency
    {
        $result = new Currency();
        $result->setCode($code);
        $result->setRates(['EUR' => 0.5]);

        return $result;
    }

    private function getRate(string $code1, string $code2): ?float
    {

        $rates = [
            'USD' => ['EUR' => 0.5, 'RUB' => 70.5],
            'EUR' => ['USD' => 2, 'RUB' => 70.5 * 2],
            'RUB' => ['EUR' => 1 / (2 * 70.5), 'USD' => 1 / 70.5],
        ];

        return array_key_exists($code1, $rates)
            && array_key_exists($code2, $rates[$code1]) ? $rates[$code1][$code2] : null;
    }
}