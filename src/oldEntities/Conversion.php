<?php

namespace App\oldEntities;

use DateTime;

class Conversion
{

    /** @var string */
    private $uid;

    /** @var bool */
    private $isExecuted;

    /** @var Amount */
    private $fromAmount;

    /** @var Amount */
    private $toAmount;

    /** @var float */
    private $rate;

    /** @var DateTime */
    private $expireAt;

    public function __construct(string $uid, Amount $fromAmount, $expireAt, $rate)
    {
        $this->uid = $uid;
        $this->isExecuted = false;
        $this->fromAmount = $fromAmount;
        $this->expireAt = $expireAt;
        $this->rate = $rate;
    }

    public function makeExecuted(Amount $toAmount)
    {
        $this->toAmount = $toAmount;
        $this->isExecuted = true;
    }

    public function isExecuted(): bool
    {
        return $this->isExecuted;
    }

    public function fromAmount(): Amount
    {
        return $this->fromAmount;
    }

    public function info(): array
    {
        return [
            'isExecuted' => $this->isExecuted,
            'fromAmount' => $this->fromAmount->info(),
            'toAmount'   => $this->toAmount === null ? [] : $this->toAmount->info(),
            'rate'       => $this->rate,
            'expireAt'   => $this->expireAt,
        ];
    }
}