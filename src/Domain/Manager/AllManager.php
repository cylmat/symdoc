<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Yaml\Yaml;

final class AllManager implements ManagerInterface
{
    private 
        $formatManager,
        $messageManager,
        $miscManager,
        $redisManager,
        $serializerManager;
    
    public function __construct(
        FormatManager $formatManager,
        MessageManager $messageManager,
        MiscManager $miscManager,
        RedisManager $redisManager,
        SerializerManager $serializerManager
    ) {
        $this->formatManager = $formatManager;
        $this->messageManager = $messageManager;
        $this->miscManager = $miscManager;
        $this->redisManager = $redisManager;
        $this->serializerManager = $serializerManager;
    }

    public function call(): array
    {
        return [
            'format' => $this->formatManager->call(),
            // 'message' => $this->messageManager->call(),
            'misc' => $this->miscManager->call(),
            'redis' => $this->redisManager->call(),
            'serializer' => $this->serializerManager->call(),
        ];
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
        DateTimeType::class;
        KernelEvent::class;
        Serializer::class;
        Yaml::class;
    }
}
