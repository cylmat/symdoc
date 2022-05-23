<?php

namespace App\Domain\Entity;

use App\Application\Form\Object\IntObject;
use App\Domain\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @see https://symfony.com/doc/5.0/validation/groups.html
 * There is 2 default validation groups:
 * - Default: Contains the constraints -that belong to no other group-
 *      in the current class AND ALL REFERENCED CLASSES (an Object property, e.g. Address class)
 * - <classname> (here User): Equivalent to all constraints ONLY of the current User object, in the Default group
 *
 * If inheritance validated with subclass User, all constraints in the User and BaseUser will be validated.
 * If validate using the base class BaseUser, then only the default constraints in the BaseUser class are validated.
 *
 * @see https://symfony.com/doc/5.0/validation/sequence_provider.html
 *  Only be validated if all of the previous groups succeeded
 *  In groups sequence -Default- now reference the current group sequence. Avoid it to avoid recursion loop
 */

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * e.g: Assert\GroupSequence({"User", "Strict"})
 * @Assert\GroupSequenceProvider
 */
class User implements GroupSequenceProviderInterface
{
    public const TYPES = ['individual', 'company'];

    public $testing;

    /** @var int $type */
    private $type = 0;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"registration"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(allowNull=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity=Token::class, mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type(type="App\Domain\Entity\Token")
     */
    private Token $token;

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
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    private $createdAt;

    # https://symfony.com/doc/current/form/data_transformers.html
    public ArrayCollection $tags; // used to Data Transformers doc
    public IntObject $intObject; // used to Data Transformers doc

    /********
     * Validation without annotations
     *    framework.validation.enable
     *    framework.validation.static_method: loadValidatorMetadata
     *
     * The validator is designed to validate objects against constraints
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('username', new Assert\NotBlank());
        $metadata->addPropertyConstraint('createdAt', new Assert\NotBlank());
        $metadata->addPropertyConstraint(
            'createdAt',
            new Assert\Type(\DateTimeImmutable::class)
        );
    }
    // -validation

    public function getGroupSequence() // from GroupSequenceProviderInterface
    {
        // if 'User' fails
        // 'Premium' and 'Api' are not validated:
        return ['User', 'Premium', 'Api'];

        // if 'User' fails, 'Premium' is also validated
        // but 'Api' won't be validated:
        return [['User', 'Premium'], 'Api'];
    }

    // MANDATORY WHEN USING EntityType
    public function __toString()
    {
        return $this->username;
    }

    public function __construct()
    {
        $this->sport = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

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

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
