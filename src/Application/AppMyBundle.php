<?php

namespace App\Application;

use App\Application\DependencyInjection\Compiler\AppCompilerPass;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppMyBundle extends Bundle
{
    public function boot(): void
    {
        // ErrorHandler::register(null, false)
        //   ->throwAt($this->container->getParameter('debug.error_handler.throw_at'), true);
        $this->container->getParameter('kernel.http_method_override');
    }

    public function build(ContainerBuilder $containerBuilder): void
    {
        parent::build($containerBuilder);

        $extClass = $this->getContainerExtensionClass();
        $ext = $this->getContainerExtension();

        // ADD compiler pass
        $containerBuilder->addCompilerPass(new AppCompilerPass());
    }

    public function registerCommands(Application $application): void
    {
        // loaded on console..
    }

    // DOC
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
