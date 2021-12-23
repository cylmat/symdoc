<?php

namespace App\Application\Controller\Basics;

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
     * @Route("/form-build")
     */
    public function formBuild(Request $request, FormCreator $formCreator): Response
    {
        $user = (new User())
            ->setUsername('user from ctrl');

        //Create a classical FormType::class
        $formBuilder = $this->createFormBuilder($user, [ // data, [options]
            'csrf_message' => 'This is an invalid Csrf...',
            'attr' => ['id' => 'form'],
            'action' => $this->generateUrl('app_application_basics_form_formbuild')
        ])
        ->setAction($this->generateUrl('app_application_basics_form_formbuild'));
        $formCreator->updateFormBuilder($formBuilder);
        $formBuilded = $formBuilder->getForm();


        // HttpFoundationRequestHandler
        $formBuilded->handleRequest($request);
        /**
         * Manually submit form
         */
        /*if ($request->isMethod(Request::METHOD_POST)) {
            $formBuilded->submit($request->request->get($formBuilded->getName()));
        }*/
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
}
