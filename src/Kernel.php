<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * optional process: Added to work with compiler pass instead of inside Bundle::build()
     */
    // @codingStandardsIgnoreStart
    public function process(ContainerBuilder $container) {}
    // -process
    protected function build(ContainerBuilder $container) {}
    // @codingStandardsIgnoreEnd

    /**
     * From MicroKernelTrait
     * registerContainerConfiguration(LoaderInterface $loader): void
    */

    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir() . '/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir() . '/config/bundles.php'));
        $container->setParameter('container.dumper.inline_class_loader', \PHP_VERSION_ID < 70400 || $this->debug);
        $container->setParameter('container.dumper.inline_factories', true);
        $confDir = $this->getProjectDir() . '/config';

        $loader->load($confDir . '/{packages}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{packages}/' . $this->environment . '/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}_' . $this->environment . self::CONFIG_EXTS, 'glob');

        // Custom
        $loader->load($confDir . '/{services}/*' . self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $routes->import($confDir . '/{routes}/' . $this->environment . '/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/{routes}/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/{routes}' . self::CONFIG_EXTS, '/', 'glob');
    }

    /**
     * Override directories
     * Templates:
     *  - twig.paths: ["%kernel.project_dir%/resources/views"]
     *  - translator.paths: ["%kernel.project_dir%/i18n"]
     *  composer "extra"."public-dir": "my_new_public_dir"
     */
    public function getProjectDir()
    {
        return parent::getprojectDir();
    }

    public function getCacheDir()
    {
        return parent::getCacheDir();
    }

    public function getLogDir()
    {
        return parent::getLogDir();
    }

    public function getCharset()
    {
        return parent::getCharset(); //return 'ISO-8859-1'; 'UTF-8';
    }
}
