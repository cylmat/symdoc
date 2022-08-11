<?php

namespace App\Domain\Manager\Advanced;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Message\AutoMessageNotification;
use App\Domain\Message\MessageNotification;
use App\Domain\Message\SyncMessageNotification;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\AddBusNameStampMiddleware;
use Symfony\Component\Messenger\Stamp\DelayStamp;

final class MessageManager implements ManagerInterface
{
    /**
     * Message: sended object
     * Serializer
     * Middleware:
     * Bus:
     * Handler:
     */

    private MessageBusInterface $messageBus;
    private KernelInterface $kernel;

    /** @todo: include receiver service in compiler pass */
    public function __construct(
        MessageBusInterface $messageBus,
        KernelInterface $kernel
    ) {
        $this->messageBus = $messageBus;
        $this->kernel = $kernel;
    }

    public function call(array $context = []): array
    {
        // bus by default: messenger.bus.default
        $envelope = $this->messageBus->dispatch(
            new MessageNotification('Message from the manager'),
            [new DelayStamp(1)]
        );

        return [
            'message_bus' => $this->messageBus,
            'envelope_dispatched' => $envelope, //, [$stamp]),
            'instant' => $this->messageBus->dispatch(new SyncMessageNotification('Instant message')),
            'send_readed' => $this->messageBus->dispatch(new AutoMessageNotification('Web reading')),
            //'readed' => $this->read(),
        ];
    }

    // HANDLERS
    /*
    messenger.middleware.add_bus_name_stamp_middleware
    messenger.middleware.dispatch_after_current_bus-
    messenger.middleware.failed_message_processing_middleware
    ...Your own
    messenger.middleware.send_message
    messenger.middleware.handle_message
    */

    private function read()
    {
        $console = new Application($this->kernel);
        $console->setAutoExit(false);
        $buffer = new StreamOutput(fopen('/tmp/stream', 'w+'));

        $console->run(
            new ArrayInput([
                'command' => 'messenger:consume',
                'receivers' => ['auto_read'],
                '--limit' => 1, // keep 1: if more than messages, will freeze page
                '--no-debug' => 1
                ]),
            $buffer,
        );

        $output = trim(fread(fopen('/tmp/stream', 'r'), 10000));

        return trim($output);
    }
}
