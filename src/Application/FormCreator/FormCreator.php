<?php

namespace App\Application\FormCreator;

use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormCreator
{
    private $form;
    private $formBuilder;
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory) {
        $this->formFactory = $formFactory;
    }

    public function create(): array
    {
        return [
            'form' => $this->getForm(),
            'formBuilder' => $this->getFormBuilder(),
            'customFormBuilder' => $this->createCustomFormBuilder(),
        ];
    }

    public function setControllerForms(FormInterface $userForm, FormBuilderInterface $userFormBuilder): self
    {
        $this->form = $userForm;
        $this->formBuilder = $userFormBuilder;

        return $this;
    }

    // Symfony\Component\Form\FormFactory()->create($type, $data, $options);
    private function getForm(): FormInterface
    {
        $userForm = $this->form;

        return $userForm;
    }

    // Symfony\Component\Form\FormFactory()->createBuilder(FormType::class, $data, $options)
    private function getFormBuilder(): FormBuilderInterface
    {
        $userFormBuilder = $this->formBuilder;
        
        $userFormBuilder
            ->add('username', Type\TextType::class)
            ->add('createdAt', Type\DateTimeType::class, [
                'input' => 'datetime_immutable', 
            ])
            ->add('save', Type\SubmitType::class, ['label' => 'Saving...']);
        
        return $userFormBuilder;
    }

    private function createCustomFormBuilder(): FormBuilderInterface
    {
        return $this->formFactory->createBuilder(Type\DateType::class);
    }
}