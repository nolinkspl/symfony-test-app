<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $uid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isExecuted;

    /**
     * @ORM\Column(type="integer")
     */
    private $amountId;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expireAt;

    /**
     * @var Amount
     * @ORM\OneToOne(targetEntity="Amount", cascade={"all"})
     * @ORM\JoinColumn(name="amount_id", referencedColumnName="id")
     */
    private $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $uid): self
    {
        $this->uid = $uid;

        return $this;
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

    public function getAmountId(): ?int
    {
        return $this->amountId;
    }

    public function setAmountId(int $amountId): self
    {
        $this->amountId = $amountId;

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

    public function setExpireAt(\DateTimeInterface $expireAt): self
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    public function info(): array
    {
        return [
            'isExecuted' => $this->isExecuted,
            'fromAmount' => $this->isExecuted ? $this->calculateBeforeExecutedAmount()->info() : $this->amount->info(),
            'toAmount'   => $this->isExecuted ? $this->amount->info() : $this->calculateAfterExecutedAmount()->info(),
            'rate'       => $this->rate,
            'expireAt'   => $this->expireAt,
        ];
    }

    private function calculateBeforeExecutedAmount(): Amount
    {
        $result = clone $this->amount;
        $result->setAmount($this->amount->getAmount() / $this->getRate());

        return $result;
    }

    public function calculateAfterExecutedAmount(): Amount
    {
        $result = clone $this->amount;
        $result->setAmount($this->amount->getAmount() * $this->getRate());

        return $result;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function execute()
    {
        $this->amount = $this->calculateAfterExecutedAmount();
        $this->isExecuted = true;
    }
}
