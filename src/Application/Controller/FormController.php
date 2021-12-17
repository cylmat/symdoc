<?php

namespace App\Application\Controller;

use App\Application\Form\ApplicationType;
use App\Application\Form\UserType;
use App\Application\FormCreator\FormCreator;
use App\Domain\Entity\User;
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
    public function index(Request $request, FormCreator $formCreator): Response
    {
        $user = (new User())
            ->setUsername('Albert')
            ->setAge(45);

        $generatedForms = (object)$formCreator->setControllerForms(
            $this->createForm(UserType::class, null, []), // type, data, [options]
            $this->createFormBuilder($user, []) // data, [options]
        )->create();

        $form = $generatedForms->form;
        $formBuilded = $generatedForms->formBuilder->getForm();

        $form->handleRequest($request);
        $formBuilded->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('info', 'Form submitted');

            // redirecting
        }

        if ($formBuilded->isSubmitted() && $formBuilded->isValid()) {
            $this->addFlash('info', 'Formbuilded sumbitted');
        }

        return $this->render('form/index.html.twig', [
            'controller_name' => 'FormController',
            'data' => [ 
                'request' => $request->request->all(),
                'form' => $generatedForms->form,
                'formBuilder' => $generatedForms->formBuilder,
                'customFormBuilder' => $generatedForms->customFormBuilder,
            ],
            'form' => $form->createView(null), // parent,
            'formBuilder' => $formBuilded->createView(null),
            'customFormBuilder' => $generatedForms->customFormBuilder->getForm()->createView(null)
        ]);
    }
}
