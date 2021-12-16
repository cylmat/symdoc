<?php

namespace App\Application\Controller;

use App\Application\Form\ApplicationType;
use App\Domain\Entity\Sport;
use App\Domain\Entity\User;
use App\Domain\Manager\FormManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FormController extends AbstractController
{
    /**
     * @Route("/form")
     * 
     * 1. Build
     * 2. Render
     * 3. Process
     */
    public function index(Request $request, FormManager $formManager): Response
    {
        $user = (new User())
            ->setUsername('Albert')
            ->setAge(45);
        $sport = (new Sport())
            ->setName('basket');

        $form = $this->createForm(ApplicationType::class, null, []); // type, data, [options]
        $formBuilder = $this->createFormBuilder($user, []);

        $generatedForms = (object)$formManager->setForms($form, $formBuilder)->call();

        return $this->render('misc/index.html.twig', [
            'controller_name' => 'MiscController',
            'data' => [
                'attributes' => $request->attributes, 
                'request' => $request->request,
                'form' => $generatedForms->form,
                'formBuilder' => $generatedForms->formBuilder,
            ],
            'form' => $generatedForms->form->createView(null), // parent,
            'formBuilder' => $generatedForms->formBuilder->getForm()->createView(null)
        ]);
    }
}
