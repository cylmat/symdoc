<?php

namespace App\Application\Form\Transformer;

use App\Application\Form\Object\IntObject;
use Symfony\Component\Form\DataTransformerInterface;

class IntToObjectTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value ? $value->getInt() : null;
    }

    public function reverseTransform($value)
    {
        return $value ? new IntObject($value) : null;
    }
}
