<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CurrencyRepository::class)
 */
class Currency
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $rates;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getRates(): array
    {
        $result = json_decode($this->rates, true);
        if (is_array($result)) {
            return $result;
        }

        return [];
    }

    public function setRates(array $rates): self
    {
        $this->rates = json_encode($rates);

        return $this;
    }
}
