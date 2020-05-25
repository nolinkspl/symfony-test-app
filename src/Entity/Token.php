<?php

namespace App\Entity;

/**
 * @ORM\Entity(repositoryClass=TokenRepository::class)
 */
class Token
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $uid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    public function __construct(string $uid, \DateTime $expiresAt)
    {
        $this->setUid($uid);
        $this->setExpiresAt($expiresAt);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function info(): array
    {
        return [
            'token'     => $this->getUid(),
            'expiresAt' => $this->getExpiresAt()->format('Y-m-d h:i:s'),
        ];
    }
}
