<?php

namespace App\Entity;

use App\Repository\ConversionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConversionRepository::class)
 */
class Conversion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isExecuted = false;

    /**
     * @ORM\Column(type="integer")
     */
    private $fromAmountId;

    /**
     * @ORM\Column(type="integer")
     */
    private $toAmountId;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expireAt;

    /**
     * @var Amount
     * @ORM\OneToOne(targetEntity="Amount", cascade={"all"})
     * @ORM\JoinColumn(name="from_amount_id", referencedColumnName="id")
     */
    private $fromAmount;

    /**
     * @var Amount
     * @ORM\OneToOne(targetEntity="Amount", cascade={"all"})
     * @ORM\JoinColumn(name="to_amount_id", referencedColumnName="id")
     */
    private $toAmount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsExecuted(): ?bool
    {
        return $this->isExecuted;
    }

    public function setIsExecuted(bool $isExecuted): self
    {
        $this->isExecuted = $isExecuted;

        return $this;
    }

    public function setFromAmountId(int $amountId): self
    {
        $this->fromAmountId = $amountId;

        return $this;
    }

    public function setToAmountId(int $amountId): self
    {
        $this->toAmountId = $amountId;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->expireAt;
    }

    public function setExpireAt(\DateTimeInterface $expireAt = null): self
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    public function setFromAmount(Amount $amount)
    {
        $this->fromAmount = $amount;

        return $this;
    }

    public function setToAmount(Amount $amount)
    {
        $this->toAmount = $amount;

        return $this;
    }

    public function info(): array
    {
        return [
            'fromAmount' => $this->fromAmount->info(),
            'toAmount'   => $this->toAmount->info(),
            'rate'       => $this->rate,
            'expireAt'   => $this->expireAt,
        ];
    }

    public function execute()
    {
        $this->toAmount->setAmount(floor($this->fromAmount->getAmount() * $this->getRate()));
        $this->fromAmount->deactivate();
        $this->toAmount->activate();
        $this->setExpireAt(null);
        $this->isExecuted = true;
    }
}
