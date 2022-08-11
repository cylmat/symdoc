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
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\AddBusNameStampMiddleware;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class MessageManager implements ManagerInterface
{
    // use traint when processing !synchronously!
    // useful for CQRS, only one sync bus and one handler
    use HandleTrait;

    # private MessageBusInterface $messageBus;

    /*****************************************
     * Message: Sended plain object
     * Serializer
     * Transport: Carry messages, e.g. database or filesystem..
     * Middleware: Add informations and call specific handlers
     * Bus: "Tunnel" that allow to dispatch messages
     * Handler: Receive message an use them (operation with data message)
     */

    private KernelInterface $kernel;

    /** @todo: include receiver service in compiler pass */
    public function __construct(
        MessageBusInterface $messengerBusDefault,
        KernelInterface $kernel
    ) {
        $this->messageBus = $messengerBusDefault;
        $this->kernel = $kernel;
    }

    // $ console messenger:consume my_async
    public function call(array $context = []): array
    {
        // bus by default: messenger.bus.default
        $envelope = $this->messageBus->dispatch(
            new MessageNotification('Message from the manager'),
            [new DelayStamp(1)]
        )->with(new DispatchAfterCurrentBusStamp()); // useful for call another handler (e.g. for db)...

        return [
            'message_bus' => $this->messageBus,
            'envelope_dispatched' => $envelope,
            'envelope' => $envelope->all(HandledStamp::class),
            'handleTrait' => $this->handle(new SyncMessageNotification('blob')),
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
