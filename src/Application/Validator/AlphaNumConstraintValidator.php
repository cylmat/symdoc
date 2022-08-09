<?php

namespace App\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

// tag validator.constraint_validator
class AlphaNumConstraintValidator extends ConstraintValidator
{
    public $message = "Not an alphanum one";

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof AlphaNumConstraint) {
            throw new UnexpectedTypeException($value, AlphaNumConstraint::class);
        }

        if (is_object($value)) {
            $value = $value->foo;
        }

        if (\is_null($value) || empty($value)) {
            return;
        }

        if (!\ctype_alnum($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
