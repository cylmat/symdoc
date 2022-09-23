<?php

namespace App\Application\DependencyInjection;

use App\Application\Service\EnableFlag;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

// https://symfony.com/doc/current/bundles/configuration.html
class AppMyExtension extends Extension implements
    ExtensionInterface,
    CompilerPassInterface,
    PrependExtensionInterface
    //,ConfigurableExtension
{
    /**
     * How to Simplify Configuration of Multiple Bundles
     * @see https://symfony.com/doc/5.0/bundles/prepend_extension.html
     *
     * Merge all extension before beeing called
     * Full access to the ContainerBuilder instance just before the load() method is called on each
     */
    public function prepend(ContainerBuilder $container): void
    {
        // get all bundles
        $bundles = $container->getParameter('kernel.bundles');
        $ext = $container->getExtensions();
        // $container->prependExtensionConfig($extName, $config);

        // used to load <ns> configurations
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('app_my.yml');
    }

    /**
     * Can't access namespaces
     * $container is an empty one, with empty $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        // can only load "service" or "import" <ns>
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        # -> will return  "no extension able to load the configuration for app_my"
        # must be called in prepend()
        #$loader->load('app_my.yml');

        $configClass = $this->getConfiguration($configs, $container);

        // (final) Load Configuration
        $arrayCurrentConfig = $this->processConfiguration($configClass, $configs);

        // Like _instanceof in services.yml
        $container->registerForAutoconfiguration(ServiceDomainInterface::class)
            ->addTag('service.my_tag2');

        $this->loadEnableFlagConfiguration($configs, $container);
    }

    # behavior feature flag
    # $configs contain the "Configuration" class loaded with yml and xml
    private function loadEnableFlagConfiguration(array $configs, ContainerBuilder $container)
    {
        // $files = (new Finder()) ->in(self::CONFIG_DIR) ->files() ->path(self::YAML_EXT_PATTERN) ->getIterator()

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('app_my.xml');

        /** @var \App\Application\DependencyInjection\Configuration $configuration */
        $configuration = $this->getConfiguration($configs, $container);
        /** @var array $this->processedConfig Array of current config file */
        $this->processedConfig = $this->processConfiguration($configuration, $configs);

        # Will not work, must be used in "process()"
        // $definition = $container->findDefinition(EnableFlag::class);
        // -> Error: You have requested a non-existent service "App\Application\Service\EnableFlag".
        //$definition->setArgument(1, $processedConfig);
    }

    // called after container is loaded (contain all services)
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(EnableFlag::class);
        # dynamically load arguments
        $definition->setArgument('$configFromExtension', [$this->processedConfig]);
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ?ConfigurationInterface
    {
        // Load extension Configuration class
        $config = parent::getConfiguration($config, $container);
        return $config;
    }

    public function getAlias(): string
    {
        // return 'app_my';
        # will be used as a base for the "Configuration" class
        return parent::getAlias();
    }
}
