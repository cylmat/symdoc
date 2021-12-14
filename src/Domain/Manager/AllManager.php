<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Config\Definition\Builder\ExprBuilder;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Yaml\Yaml;

final class AllManager implements ManagerInterface
{
    private 
        $expressionManager,
        $formatManager,
        $messageManager,
        $miscManager,
        $redisManager,
        $serializerManager;
    
    public function __construct(
        ExpressionManager $expressionManager,
        FormatManager $formatManager,
        MessageManager $messageManager,
        MiscManager $miscManager,
        RedisManager $redisManager,
        SerializerManager $serializerManager
    ) {
        $this->expressionManager = $expressionManager;
        $this->formatManager = $formatManager;
        $this->messageManager = $messageManager;
        $this->miscManager = $miscManager;
        $this->redisManager = $redisManager;
        $this->serializerManager = $serializerManager;
    }

    public function call(): array
    {
        return [
            'expression' => $this->expressionManager->call(),
            'format' => $this->formatManager->call(),
            // 'message' => $this->messageManager->call(),
            'expression' => $this->expressionManager->call(),
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
        AnnotationLoader::class;
        DateTimeType::class;
        ExprBuilder::class;
        ExpressionLanguage::class;
        Filesystem::class;
        Finder::class;
        Kernel::class;
        Serializer::class;
        VarDumper::class;
        Yaml::class;
        
        // events
        FormEvents::class;
        KernelEvent::class;
    }
    
    public function factory()
    {
        ConfigCacheFactory::class;
        ClassMetadataFactory::class;
    }
}
