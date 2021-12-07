<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Entity\User;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class MiscManager implements ManagerInterface
{
    public function call(): array
    {
        $property = $this->properties();

        return [
            'property' => $property
        ];
    }

    private function properties(): string
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $person = new User();
        $person->testing = 'Alpha';

        $testing = $accessor->getValue($person, 'testing');

        return $testing;
    }
}
