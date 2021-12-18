<?php

namespace App\Application\Controller;

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
    public function form(Request $request, FormCreator $formCreator): Response
    {
        $user = (new User())
            ->setUsername('Albert')
            ->setAge(45);

        $generatedForms = (object)$formCreator->setControllerForms(
            // FORM
            $this->createForm(UserType::class, $user,  [ // type, data, [options]
                'custom_type_options_file_required' => [false, true]
            ]),
            null
        )->create();

        $form = $generatedForms->form;
        $form->handleRequest($request);

         // validate if object valid after submitted data to it
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('info', 'Form submitted');

            d($form->get('agreeTerms')->getData());
            // redirecting
        }

        return $this->render('form/form.html.twig', [
            'controller_name' => 'FormController',
            'data' => [ 
                'request' => $request->request->all(),
                'generatedForms' => $generatedForms,
                'form' => $form,
                'form.view' => $form->createView(null),
            ],
            'form' => $form->createView(null), // parent,
        ]);
    }

    /**
     * @Route("/formbuild")
     */
    public function formBuild(Request $request, FormCreator $formCreator): Response
    {
        $user = (new User())
            ->setUsername('Albert')
            ->setAge(45);

        $generatedForms = (object)$formCreator->setControllerForms(null,
            // FORM BUILDER
            $this->createFormBuilder($user, [ // data, [options]
                'action' => $this->generateUrl('app_application_form_formbuild')
            ])->setAction($this->generateUrl('app_application_form_formbuild'))
        )->create();

        $formBuilded = $generatedForms->formBuilder->getForm();
        $customFormBuilded = $generatedForms->customFormBuilder->getForm();

        $formBuilded->handleRequest($request);

        if ($formBuilded->isSubmitted() && $formBuilded->isValid()) {
            $this->addFlash('info', 'Formbuilded sumbitted');
        }

        return $this->render('form/form_build.html.twig', [
            'controller_name' => 'FormController',
            'data' => [ 
                'request' => $request->request->all(),
                'generatedForms' => $generatedForms,
                'formBuilder' => $formBuilded,
                'formBuilder.view' => $formBuilded->createView(null),
                'customFormBuilder' => $customFormBuilded,
            ],
            'formBuilder' => $formBuilded->createView(null),
            'customFormBuilder' => $customFormBuilded->createView(null)
        ]);
    }
}
