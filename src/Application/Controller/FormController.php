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
        $user = (new User());

        $form = $this->createForm(UserType::class, $user,  [ // type, data, [options]
            'custom_type_options_file_required' => [false, true]
        ]);
        $formCreator->updateForm($form);

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
                'form' => $form,
                'form.view' => $form->createView(null),// parent,
            ],
            'form' => $form->createView(null),
        ]);
    }

    /**
     * @Route("/formbuild")
     */
    public function formBuild(Request $request, FormCreator $formCreator): Response
    {
        $user = (new User())
            ->setUsername('username from controller');

        //Create a classical FormType::class
        $formBuilder = $this->createFormBuilder($user, [ // data, [options]
            'csrf_message' => 'This is an invalid Csrf...',
        ]);
        $formCreator->updateFormBuilder($formBuilder);
        $formBuilded = $formBuilder->getForm();

        $formBuilded->handleRequest($request);
        if ($formBuilded->isSubmitted() && $formBuilded->isValid()) {
            $this->addFlash('info', 'Formbuilded submitted');

            $submitted = $formBuilded->getData();
        }

        return $this->render('form/form_build.html.twig', [
            'controller_name' => 'FormController',
            'data' => [ 
                'request' => $request->request->all(),
                'formBuilder' => $formBuilder,
                'formBuilded' => $formBuilded,
                'formBuilded.view' => $formBuilded->createView(null),
            ],
            'formBuilder' => $formBuilded->createView(null),
            'submitted' => $submitted ?? null,
        ]);
    }

    /**
     * @Route("/formbuildcustom")
     */
    public function formBuildCustom(Request $request, FormCreator $formCreator): Response
    {
        $formBuilder = $this->createFormBuilder(null, [ // data, [options]
            'action' => $this->generateUrl('app_application_form_formbuildcustom')
        ])
        ->setAction($this->generateUrl('app_application_form_formbuildcustom'));

        $formBuilded = $formBuilder->getForm();

        $formBuilded->handleRequest($request);
        if ($formBuilded->isSubmitted() && $formBuilded->isValid()) {
            $this->addFlash('info', 'Formbuilded sumbitted');
        }

        return $this->render('form/form_build_custom.html.twig', [
            'controller_name' => 'FormController',
            'data' => [ 
                'request' => $request->request->all(),
                'customFormBuilder' => $formBuilder,
            ],
            'customFormBuilder' => $formBuilder->getForm()->createView(null),
        ]);
    }
}
