<?php

namespace App\oldEntities;

use DateTime;

class Token
{

    /** @var int $uid */
    private $uid;

    /** @var DateTime $expiresAt */
    private $expiresAt;

    public function __construct(string $uid, DateTime $expired)
    {
        $this->uid = $uid;
        $this->expiresAt = $expired;
    }

    /**
     * @return int
     */
    public function uid(): int
    {
        return $this->uid;
    }

    /**
     * @return DateTime
     */
    public function expiresAt(): DateTime
    {
        return $this->expiresAt;
    }
}
