<?php

namespace App\Entity;

use App\Repository\AmountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AmountRepository::class)
 */
class Amount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $currency_id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrencyId(): ?int
    {
        return $this->currency_id;
    }

    public function setCurrencyId(int $currency_id): self
    {
        $this->currency_id = $currency_id;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
