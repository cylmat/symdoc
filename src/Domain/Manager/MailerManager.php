<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Crypto\SMimeSigner;
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
            ->addDateHeader('this-date', new DateTime())
            ->addTextHeader('X-Transport', 'alternative');

        $e = '';
        $sentMessage = null;
        try {
            $sentMessage = $this->mailer->send($email);
        } catch (TransportExceptionInterface | \Exception $err) {
            $e .= $err->getMessage();
        }

        $tplEmail = (new TemplatedEmail())
            ->from('test@example.com')
            ->to(new Address('test2@example.com'))
            ->subject('Hello!')
            ->htmlTemplate('emails/template.html.twig')
            // or ->textTemplate('emails/template.html.twig')
            ->context([
                'username' => 'foo',
            ])
        ;

        // tpl //
        // composer require twig/extra-bundle twig/cssinliner-extra
        // composer require twig/extra-bundle twig/markdown-extra league/commonmark
        // composer require twig/extra-bundle twig/inky-extra
        // https://get.foundation/emails/docs/inky.html

        // "email" is WrappedTemplatedEmail
        // email.image()

        //////////////
        // Security //
        //////////////
        // S/MIME + OpenSSL PHP extension

        //$signer = new SMimeSigner('/path/to/certificate.crt', '/path/to/certificate-private-key.key', 'pass');
        //$signedEmail = $signer->sign($email);

        //$encrypter = new SMimeEncrypter('/path/to/certificate.crt');
        //$encryptedEmail = $encrypter->encrypt($email);

        return [
            $email,
            $tplEmail,
            $sentMessage,
            $e
        ];
    }
}
