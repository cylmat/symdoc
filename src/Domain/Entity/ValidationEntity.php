<?php

namespace App\Domain\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlankValidator;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

/**
 * @Assert\GroupSequenceProvider
 *
 * @Assert\Expression(expression="'isok' === this.getExpress()", message="class invalid!")
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
