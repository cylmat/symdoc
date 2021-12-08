<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Entity\User;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class MiscManager implements ManagerInterface
{
    public function call(): array
    {
        return [
            'property' => $this->properties(),
            'resolver' => $this->resolver(),
        ];
    }

    private function resolver(): array
    {
        $resolver = new OptionsResolver();

        $resolver->setDefault('encryption', null);
        $resolver->setDefault('port', function(Options $options) {
            if ('ssl' === $options['encryption']) {
                return 465;
            }

            return 25;
        });
        // will be Callable itself
        $resolver->setDefault('port-closure', function(/* implicite "Options" argument */) {
            /** @var Options $options */
            if ('ssl' === $options['encryption']) {
                return 465;
            }

            return 25;
        });

        return $resolver->resolve(['encryption' => 'SSL']);
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
