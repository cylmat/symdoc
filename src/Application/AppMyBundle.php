<?php

namespace App\Application;

use App\Application\DependencyInjection\Compiler\AppCompilerPass;
use App\Application\DependencyInjection\Compiler\TranslatorCompilerPass;
use App\Domain\Service\ServiceDomainInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\EventDispatcher\DependencyInjection\AddEventAliasesPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @see https://symfony.com/doc/5.0/bundles.html
 * @see https://symfony.com/doc/5.0/contributing/code/standards.html
 *
 * Command/
 * Controller/
 * DependencyInjection/
 * Document/
 * Entity/
 * EventListener/
 * Resources/config/
 * Resources/config/serialization/
 * Resources/config/validation/
 * Resources/public/
 * Resources/translations/
 * Resources/views/
 * Tests/
 */
class AppMyBundle extends Bundle
{
    public function boot(): void
    {
        // ErrorHandler::register(null, false)
        //   ->throwAt($this->container->getParameter('debug.error_handler.throw_at'), true);
        $this->container->getParameter('kernel.http_method_override');
    }

    /**
     * ContainerBuilder can be get from Bundle, Extension or Kernel
     */
    public function build(ContainerBuilder $containerBuilder): void
    {
        parent::build($containerBuilder);

        $extClass = $this->getContainerExtensionClass();
        $ext = $this->getContainerExtension();

        // ADD compiler pass
        $containerBuilder->addCompilerPass(new AppCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 0); //default
        // PassConfig::TYPE_AFTER_REMOVING is called after removing unused services
        $containerBuilder->addCompilerPass(new TranslatorCompilerPass());

        // Like _instanceof in services.yml
        $containerBuilder->registerForAutoconfiguration(ServiceDomainInterface::class)
            ->addTag('service.my_tag2');

        /**
         * For events
         */
        $containerBuilder->addCompilerPass(new AddEventAliasesPass([
            'my_custom::class' => 'my_custom_event',
        ]));
    }

    public function registerCommands(Application $application): void
    {
        // loaded on console..
    }

    // Overridden to allow for the custom extension alias.
    public function getContainerExtension(): ?ExtensionInterface
    {
        // return new AppMyExtension();
        return parent::getContainerExtension();
    }

    protected function getContainerExtensionClass()
    {
        return parent::getContainerExtensionClass();
    }

    protected function createContainerExtension()
    {
        return parent::createContainerExtension();
    }

    public function shutdown()
    {
    }
}
