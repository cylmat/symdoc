<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use DateTime;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

final class MailerManager implements ManagerInterface
{
    private TransportInterface $mailer;
    private MailerInterface $mailerWithoutDebug; // ->send()

    public function __construct(TransportInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function call(array $context = []): array
    {
        $email = (new Email())
            ->from(Address::fromString('Check <last@sample.com>'))
            ->to('you@sample.com')
            ->addTo(new Address('fromaddress@address.com', 'client'))
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('hotkey@samlpe.alpha')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Try it again!')

            //********************** content
            ->text('Sending email')
            //->text(fopen('/path/to/emails/signin', 'r'))
            ->html('<p>My template!</p>')

            //********************** attach
            ->attachFromPath('/path/to/documents/file.pdf', 'Contract', 'application/msword')
            ->embedFromPath('/path/to/images/mickey.gif', 'holidays')
            ->html('<img src="cid:logo"> ... <img src="cid:holidays"> ...')
        ;

        $email->getHeaders()
            ->addDateHeader('this-date', new DateTime());

        $e = '';
        try {
            $sentMessage = $this->mailer->send($email);
        } catch (TransportExceptionInterface | \Exception $err) {
            $e .= $err->getMessage();
        }

        return [
            $email, $e
        ];
    }
}
