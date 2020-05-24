<?php

namespace App\oldEntities;

class Amount
{

    /** @var string */
    private $currency;

    /** @var double */
    private $amount;

    public function __construct(string $currency, float $amount)
    {
        $this->currency = $currency;
        $this->amount = $amount;
    }

    public function info(): array
    {
        return [
            'currency' => $this->currency,
            'amount'   => $this->amount,
        ];
    }
}