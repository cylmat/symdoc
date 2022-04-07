<?php

namespace App\Application\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AppMyExtension extends Extension implements PrependExtensionInterface
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
        $loader->load('config.yml');
    }

    /**
     * Can't access namespaces
     * $container is an empty one, with empty $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        // can only load "service" or "import" <ns>
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('service.yml');

        $configClass = $this->getConfiguration($configs, $container);

        // (final) Load Configuration
        $arrayCurrentConfig = $this->processConfiguration($configClass, $configs);

        // Like _instanceof in services.yml
        $container->registerForAutoconfiguration(ServiceDomainInterface::class)
            ->addTag('service.my_tag2');
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ?ConfigurationInterface
    {
        return parent::getConfiguration($config, $container);
    }

    public function getAlias(): string
    {
        // return 'app_my_config';
        return parent::getAlias();
    }
}
