<?php

namespace App\Domain\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

/**
 * @Assert\GroupSequenceProvider
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

    public function getGroupSequence(): array
    {
        return [['foo', 'Valy'], 'three', 'Default'];
    }
}
