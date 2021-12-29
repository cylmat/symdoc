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
     * Merge all extension before beeing called
     */
    public function prepend(ContainerBuilder $container): void
    {
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
        $config = $this->processConfiguration($configClass, $configs);
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
