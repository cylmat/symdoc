<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Yaml\Yaml;

final class AllManager implements ManagerInterface
{
    public function call(): array
    {
        return [];
    }

    public function interfaces()
    {
        AuthenticationProviderInterface::class;
        DataCollectorInterface::class;
        DataTransformerInterface::class;
        FormTypeInterface::class;
    }
    
    public function classes()
    {
        ConfigCacheFactory::class;
        KernelEvent::class;
        Serializer::class;
        Yaml::class;
    }
}
