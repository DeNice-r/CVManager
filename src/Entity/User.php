<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $representsCompany = false; // False - user; True - company

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $activeCVs = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $additionalInfo = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        if ($this->representsCompany) {
            $roles[] = 'ROLE_COMPANY';
        } else {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        if (1 == count($roles) && 'ROLE_USER' == $roles[0]) {
            if ($this->representsCompany) {
                $this->roles[] = 'ROLE_COMPANY';
            } else {
                $this->roles[] = 'ROLE_USER';
            }
        }
        $this->roles = array_unique($this->roles);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRepresentsCompany(): bool
    {
        return $this->representsCompany;
    }

    public function setRepresentsCompany(bool $isCompany): self
    {
        $this->representsCompany = $isCompany;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getActiveCVs(): ?array
    {
        return $this->activeCVs;
    }

    public function addActiveCV(?int $cvreactionId): self
    {
        if(!isset($this->activeCVs))
            $this->activeCVs = array();
        $this->activeCVs[] = $cvreactionId;
        return $this;
    }

    public function removeActiveCV(?int $cvreactionId): self
    {
        if(!isset($this->activeCVs))
            return $this;
        if (($key = array_search($cvreactionId, $this->activeCVs)) !== false) {
            unset($this->activeCVs[$key]);
        }
        return $this;
    }

    public function setActiveCVs(?array $activeCVs): self
    {
        $this->activeCVs = $activeCVs;

        return $this;
    }
    

    public function getAdditionalInfo(): ?array
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(?array $additionalInfo): self
    {
        $this->additionalInfo = $additionalInfo;

        return $this;
    }
}
