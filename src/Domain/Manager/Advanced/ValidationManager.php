<?php

namespace App\Domain\Manager\Advanced;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Entity\ValidationEntity;
use Symfony\Component\Validator\Constraints\Isbn;
use Symfony\Component\Validator\Constraints\Negative;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidationManager implements ManagerInterface
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function call(array $context = []): array
    {
        // default annotations validation
        $entity = new ValidationEntity();
        $entity->foo = 'alpha';
        $entity->foo2 = 'beta';
        $entity->valy = 'delta';
        $annotation_errors = $this->validator->validate($entity);

        ////////////////
        // custom one //
        // validation use annotation and .xml
        $entity->bar2 = 'from annotation';
        $entity->three = 'from xml';

        // constraints should be "null" to use XML config framework validation
        // return a ConstraintViolationList
        $errors = $this->validator->validate($entity, null, 'ValidationEntity');

        return [
            'entity' => $entity,
            'annotation errors' => (string) $annotation_errors,
            'custom errors' => (string) $errors,
            'not-isbn' => $this->validator->validate('test', [new Isbn()]),
        ];
    }
}
