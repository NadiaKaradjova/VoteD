<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 */
class Vote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vote;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $ip;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $randomKey;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdOn;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedOn;

    public function __construct()
    {
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVote(): ?string
    {
        return $this->vote;
    }

    public function setVote(string $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getCreatedOn(): ?\DateTime
    {
        return $this->createdOn;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getKey()
    {
        return $this->randomKey;
    }

    public function setKey($key): void
    {
        $this->randomKey = $key;
    }

    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn(): void
    {
        $this->updatedOn = new \DateTime();
    }

}
