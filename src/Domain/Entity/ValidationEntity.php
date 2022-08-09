<?php

namespace App\Domain\Entity;

use App\Application\Validator\AlphaNumConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlankValidator;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

/**
 * @Assert\GroupSequenceProvider
 *    or
 * -@-Assert\GroupSequence({"ValidationEntity", "Sample"})
 *          (in Sequence, "Default" is the group itself)
 *
 * @Assert\Expression(expression="'isok' === this.getExpress()", message="class invalid!")
 * @AlphaNumConstraint
 *
 * https://symfony.com/doc/5.0/validation/groups.html
 *
 * - Default: constraints in the current class -and all referenced classes- that belong to !no other group!
 * - <classname>: Equivalent to all constraints of only the current object in the Default group
 */
class ValidationEntity implements GroupSequenceProviderInterface
{
    /**
     * @Assert\NotBlank(groups={"foo"})
     */
    public $foo;

    /**
     * @Assert\NotBlank(groups={"foo"})
     */
    public $foo2;

    /**
     * @Assert\NotBlank(groups={"Valy"})
     */
    public $valy;

    /**
     * @Assert\NotBlank()
     */
    public $bar2;

    /**
     * @Assert\Url(payload={"custom"="iswarning"}, message="custom.length.min")
     */
    public $three;

    /*
     * @Assert\Valid() => embedded objects
     */
    // public object

    /**
     * @Assert\IsTrue()
     */
    public function hasGoodValue(string $whatever = 'test'): bool
    {
        return true;
    }

    public function getExpress(): string
    {
        return 'isok';
    }

    public function getGroupSequence(): array
    {
        return [['foo', 'Valy'], 'three', 'Default'];
    }
}
