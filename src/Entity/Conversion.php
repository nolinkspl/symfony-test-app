<?php

namespace App\Entity;

use App\Repository\ConversionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

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

    public function isExecuted(): ?bool
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

    public function rate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function expireAt(): ?\DateTimeInterface
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

    public function fromAmount(): Amount
    {
        return $this->fromAmount;
    }

    public function toAmount(): Amount
    {
        return $this->toAmount;
    }

    public function execute()
    {
        $this->toAmount->setAmount(floor($this->fromAmount->getAmount() * $this->rate()));
        $this->fromAmount->deactivate();
        $this->toAmount->activate();
        $this->setExpireAt(null);
        $this->isExecuted = true;
    }

    public function setDefaultExpireAt(): self
    {
        $this->setExpireAt(new \DateTime('+1 minute'));

        return $this;
    }
}
