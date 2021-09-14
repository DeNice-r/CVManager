<?php

namespace App\Entity;

use App\Repository\CVReactionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CVReactionsRepository::class)
 */
class CVReactions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $CVID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $companyName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendOn;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reaction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCVID(): ?int
    {
        return $this->CVID;
    }

    public function setCVID(int $CVID): self
    {
        $this->CVID = $CVID;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getSendOn(): ?\DateTimeInterface
    {
        return $this->sendOn;
    }

    public function setSendOn(\DateTimeInterface $sendOn): self
    {
        $this->sendOn = $sendOn;

        return $this;
    }

    public function getReaction(): ?bool
    {
        return $this->reaction;
    }

    public function setReaction(bool $reaction): self
    {
        $this->reaction = $reaction;

        return $this;
    }
}
