<?php

namespace App\Domain\Manager\Advanced;

use App\Application\Validator\AlphaNumConstraint;
use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Entity\ValidationEntity;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Isbn;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidationManager implements ManagerInterface
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validation on :
     *  - public or private propertie
     *  - getter "get", "is" or "has"
     *  - class
     */
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
        $payload = $errors[0]->getConstraint()->payload;

        return [
            'entity' => $entity,
            'annotation errors' => (string) $annotation_errors,
            'custom errors' => (string) $errors,
            'payload' => $payload,
            'not-isbn' => $this->validator->validate('test', [new Isbn()]),
            'blank' => [
                (string) $this->validator->validate('', [new Blank()]),
                (string) $this->validator->validate('0', [new Blank()]), // 0 is not blank
            ],
            'alphanum' => (string) $this->validator->validate('blob', [new AlphaNumConstraint()]), // 0 is not blank
            'callback' => (string) $this->validator->validate('test-fail', [
                new Callback(['callback' => function (string $value, ExecutionContext $ctx) {
                    if ($value !== 'test') {
                        $ctx->buildViolation('no testing!')
                            ->atPath('called')
                            ->addViolation();
                    }
                }])
            ]),
        ];
    }
}
