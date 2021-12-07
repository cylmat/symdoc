<?php

namespace App\Application\Service;

use Symfony\Component\PropertyAccess\PropertyAccess;

class Properties
{
    public function access()
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $person = new Person();
        $person->firstName = 'Wouter';

        $firstName = $accessor->getValue($person, 'firstName');
        d($firstName);
        die();
    }
}