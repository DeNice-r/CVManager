<?php

namespace App\Entity;

use App\Repository\CVReactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CVReactionRepository::class)
 */
class CVReaction
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
    private $cvId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $companyName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sentOn;

    /**
     * @ORM\Column(type="integer")
     */
    private $companyId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CVVersion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CVName;

    /**
     * @ORM\Column(type="string", length=4096)
     */
    private $CVText;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CVPosition;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $reaction;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    public function unpackCV(CV $cv): self
    {
        $this->setcvId($cv->getId());
        $this->setCVName($cv->getName());
        $this->setCVText($cv->getText());
        $this->setCVPosition($cv->getPosition());
        $this->setCVVersion($cv->getEditedOn());
        return $this;        
    }

    public function unpackCompany(User $company): self
    {
        $this->setCompanyId($company->getId());
        $this->setCompanyName($company->getName());
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getcvId(): ?int
    {
        return $this->cvId;
    }

    public function setcvId(int $cvId): self
    {
        $this->cvId = $cvId;

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

    public function getSentOn(): ?\DateTimeInterface
    {
        return $this->sentOn;
    }

    public function setSentOn(\DateTimeInterface $sentOn): self
    {
        $this->sentOn = $sentOn;

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

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function setCompanyId(int $companyId): self
    {
        $this->companyId = $companyId;

        return $this;
    }

    public function getCVVersion(): ?\DateTimeInterface
    {
        return $this->CVVersion;
    }

    public function setCVVersion(\DateTimeInterface $CVVersion): self
    {
        $this->CVVersion = $CVVersion;

        return $this;
    }

    public function getCVName(): ?string
    {
        return $this->CVName;
    }

    public function setCVName(string $CVName): self
    {
        $this->CVName = $CVName;

        return $this;
    }

    public function getCVText(): ?string
    {
        return $this->CVText;
    }

    public function setCVText(string $CVText): self
    {
        $this->CVText = $CVText;

        return $this;
    }

    public function getCVPosition(): ?string
    {
        return $this->CVPosition;
    }

    public function setCVPosition(string $CVPosition): self
    {
        $this->CVPosition = $CVPosition;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
