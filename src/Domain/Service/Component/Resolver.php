<?php

namespace App\Domain\Service\Component;

use App\Domain\Service\ServiceDomainInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Resolver implements ServiceDomainInterface
{
    public function use(): array
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
}
