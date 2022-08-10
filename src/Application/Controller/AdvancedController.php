<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\Advanced\MailerManager;
use App\Domain\Manager\Advanced\MessageManager;
use App\Domain\Manager\Advanced\SerializerManager;
use App\Domain\Message\MessageNotification;
use App\Domain\Manager\Advanced\ValidationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class AdvancedController extends AbstractController
{
    /**
     * @Route("/message")
     */
    public function message(MessageManager $messageManager): Response
    {
        $this->dispatchMessage(new MessageNotification('From controller'));
        return new Response([
            'data' => $messageManager->call(),
        ]);
    }

    /**
     * @Route("/serializer")
     */
    public function serializer(SerializerManager $serializerManager): Response
    {
        return new Response([
            'data' => $serializerManager->call(),
        ]);
    }

    /**
     * @Route("/mailer")
     *
     * composer require symfony/amazon-mailer
     * composer require symfony/google-mailer
     * composer require symfony/sendgrid-mailer
     */
    public function mailer(MailerManager $mailer): Response
    {
        return new Response([
            'data' => $mailer->call(),
        ]);
    }

    /**
     * @Route("/validation")
     */
    public function validation(ValidationManager $validation)
    {
        return new Response([
            'data' => $validation->call(),
        ]);
    }
}
