<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Config\Definition\Builder\ExprBuilder;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\VarDumper\VarDumper;
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
        AnnotationLoader::class;
        Constraint::class;
        DateTimeType::class;
        ExprBuilder::class;
        ExpressionLanguage::class;
        Filesystem::class;
        Finder::class;
        InputArgument::class;
        Kernel::class;
        Response::class;
        Serializer::class;
        VarDumper::class;
        Yaml::class;

        // events
        FormEvents::class;
        KernelEvent::class;
    }

    public function form()
    {
        FormType::class;
    }

    public function factory()
    {
        ConfigCacheFactory::class;
        ClassMetadataFactory::class;
    }

    public function security()
    {
        AccessDecisionManager::class;
        Security::class;
        UserChecker::class;
    }
}
