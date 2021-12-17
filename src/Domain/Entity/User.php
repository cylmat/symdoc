<?php

namespace App\Domain\Entity;

use App\Domain\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * DOC
 * 
 * There is 2 default validation groups
 * - <classname> (here User)
 * - Default
 */

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @todo NotBlank(groups={"registration"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @todo (with serialize) Assert\NotBlank
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    public $testing;

    /**
     * @ORM\OneToOne(targetEntity=Token::class, mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $token;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    /**
     * @ORM\OneToMany(targetEntity=Sport::class, mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $sport;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    public function __construct()
    {
        $this->sport = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * - Validation without annotations
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('username', new NotBlank());
        $metadata->addPropertyConstraint(
            'createdAt',
            new Type(\DateTimeImmutable::class)
        );
    }
    // -validation

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
        
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getToken(): ?Token
    {
        return $this->token;
    }

    public function setToken(Token $token): self
    {
        // set the owning side of the relation if necessary
        if ($token->getUser() !== $this) {
            $token->setUser($this);
        }

        $this->token = $token;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection|Sport[]
     */
    public function getSport(): Collection
    {
        return $this->sport;
    }

    public function addSport(Sport $sport): self
    {
        if (!$this->sport->contains($sport)) {
            $this->sport[] = $sport;
            $sport->setUser($this);
        }

        return $this;
    }

    public function removeSport(Sport $sport): self
    {
        if ($this->sport->removeElement($sport)) {
            // set the owning side to null (unless already changed)
            if ($sport->getUser() === $this) {
                $sport->setUser(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
