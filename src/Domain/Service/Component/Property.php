<?php

namespace App\Domain\Service\Component;

use App\Domain\Entity\User;
use App\Domain\Service\ServiceDomainInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Property implements ServiceDomainInterface
{
    public function use(): array
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $person = new User();
        $person->testing = 'Alpha';

        $testing = $accessor->getValue($person, 'testing');

        return [$testing];
    }
}
