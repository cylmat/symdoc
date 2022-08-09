<?php

namespace App\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS", "PROPERTY", "METHOD", "ANNOTATION"})
 */
class AlphaNumConstraint extends Constraint
{
    public $message = "Not an alphanum one";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
