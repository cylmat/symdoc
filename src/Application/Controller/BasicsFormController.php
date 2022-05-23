<?php

namespace App\Application\Controller;

use App\Application\Form\UserType;
use App\Application\FormCreator\FormCreator;
use App\Application\Response;
use App\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class BasicsFormController extends AbstractController
{
    /**
     * 1. Build
     * 2. Render
     * 3. Process
     *
     * composer require symfony/security-csrf
     * tags: [form.type_guesser]
     *
     * @todo: https://symfony.com/doc/current/form/form_collections.html
     */
    public function form(Request $request, FormCreator $formCreator): Response
    {
        $user = (new User());

        $form = $this->createForm(UserType::class, $user, [ // type, data, [options]
            'custom_type_options_file_required' => [false, true],
            'validation_groups' => ['own_registration'],
        ]);
        $formCreator->updateForm($form);
        $csrf_tokenized = null;

        $data = null;
        $form->handleRequest($request);
        // or
        if ($request->isMethod('POST') && false) {
            $form->submit($request->request->get($form->getName()));
        }

         // validate if object valid after submitted data to it
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('info', 'Form submitted');

            $data = $form->getData();
            //$form->get('saveAndAdd')->isClicked();
            //$form->getClickedButton() === $form->get('saveAndAdd');

            if ($submittedToken = $request->request->get('_token')) {
                $csrf_tokenized = $submittedToken;
                if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                    // ...
                }
            }

            // redirecting
        }

        return new Response([
            'data' => [
                'request' => $request->request->all(),
                'form' => $form,
                'form.view' => $form->createView(null),// parent,
                'csrf' => $csrf_tokenized,
                'data' => $data,
            ],
            'form' => $form->createView(null),
        ]);
    }

    public function formBuild(Request $request, FormCreator $formCreator): Response
    {
        $user = (new User())
            ->setUsername('user from ctrl');

        //Create a classical FormType::class
        $formBuilder = $this->createFormBuilder($user, [ // data, [options]
            'csrf_message' => 'This is an invalid Csrf...',
            'attr' => ['id' => 'form'],
            'action' => $this->generateUrl('app_application_basics_formbuild')
        ])
        ->setAction($this->generateUrl('app_application_basics_formbuild'));

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

        return new Response([
            'data' => [
                'request' => $request->request->all(),
                'formBuilder' => $formBuilder,
                'formBuilded' => $formBuilded,
                'formBuilded.view' => $formBuilded->createView(),
            ],
            'formb' => $formBuilded->createView(),
            'submitted' => $submitted ?? null,
        ]);
    }
}
