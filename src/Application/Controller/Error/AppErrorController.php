<?php

namespace App\Application\Controller\Error;

use HttpException;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

// overrided in framework.yaml
class AppErrorController // hard to extends ErrorController! ...
{
    private $kernel;
    private $controller;
    private $errorRenderer;

    public function __construct(HttpKernelInterface $kernel, $controller, ErrorRendererInterface $errorRenderer)
    {
        $this->kernel = $kernel;
        $this->controller = $controller;
        $this->errorRenderer = $errorRenderer;
    }

    // Symfony\Component\ErrorHandler\ErrorRenderer\SerializerErrorRenderer
    public function __invoke(\Throwable $exception): Response
    {
        $custom = new $exception(
            $exception->getMessage() . ' <- my bad!',
            $exception->getCode(),
            $exception
        );

        $exception = $this->errorRenderer->render($custom);

        return new Response(
            $exception->getAsString(),
            $exception->getStatusCode(),
            $exception->getHeaders()
        );
    }

    public function preview(Request $request, int $code): Response
    {
        /*
         * This Request mimics the parameters set by
         * \Symfony\Component\HttpKernel\EventListener\ErrorListener::duplicateRequest, with
         * the additional "showException" flag.
         */
        $subRequest = $request->duplicate(null, null, [
            '_controller' => $this->controller,
            'exception' => new HttpException($code, 'This is a sample exception.'),
            'logger' => null,
            'showException' => false,
        ]);

        return $this->kernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }
}
