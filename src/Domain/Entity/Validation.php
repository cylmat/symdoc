<?php

namespace App\Domain\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

// @todo: try this

/**
 * @Assert\GroupSequenceProvider
 */
class Validation implements GroupSequenceProviderInterface
{
    /**
     * @Assert\NotBlank(groups={"foo"})
     */
    private $foo;

    /**
     * @Assert\NotBlank(groups={"foo"})
     */
    private $foo2;

    /**
     * @Assert\NotBlank(groups={"Product"})
     */
    private $bar;

    /**
     * @Assert\NotBlank()
     */
    private $bar2;

    public function getGroupSequence(): array
    {
        return [['foo', 'Product']];
    }
}