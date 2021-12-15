<?php

namespace App\Application\Controller;

use App\Application\Form\ApplicationType;
use App\Domain\Manager\MiscManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class MiscController extends AbstractController
{
    /**
     * @Route("/misc")
     */
    public function misc(MiscManager $miscManager): Response
    {
        return $this->render('misc/index.html.twig', [
            'controller_name' => 'MiscController',
            'data' => $miscManager->call(),
        ]);
    }

    /**
     * @Route("/form")
     */
    public function form(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $forms = $this->forms($request);

        return $this->render('misc/index.html.twig', [
            'controller_name' => 'MiscController',
            'data' => ['attributes' => $request->attributes, 'request' => $request->request],
            'form' => $forms->form,
            'formBuilder' => $forms->formBuilder
        ]);
    }

    private function forms(Request $request): object
    {
        $form = $this->createForm(ApplicationType::class, null, []); // type, data, [options]
        $formBuilder = $this->createFormBuilder(null, []); // data, [options]

        return (object)[
            'form' => $form->createView(null), // parent
            'formBuilder' => $formBuilder->getForm()->createView(null)
        ];
    }
}
