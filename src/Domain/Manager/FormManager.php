<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormManager implements ManagerInterface
{
    private $form;
    private $formBuilder;
    private $customForm;

    public function __construct(
        FormFactoryInterface $customForm
    ) {
        $this->customForm = $customForm;
    }

    public function call(): array
    {
        return [
            'form' => $this->getForm(),
            'formBuilder' => $this->getFormBuilder(),
            'customFormBuilder' => $this->getCustomFormBuilder(),
        ];
    }

    public function setForms(FormInterface $form, FormBuilderInterface $formBuilder): self
    {
        $this->form = $form;
        $this->formBuilder = $formBuilder;

        return $this;
    }

    // Symfony\Component\Form\FormFactory()->create($type, $data, $options);
    private function getForm(): FormInterface
    {
        $form = $this->form;

        return $form;
    }

    // Symfony\Component\Form\FormFactory()->createBuilder(FormType::class, $data, $options)
    private function getFormBuilder(): FormBuilderInterface
    {
        $formBuilder = $this->formBuilder;
        d($formBuilder);

        $formBuilder
            ->add('username', Type\TextType::class)
            ->add('createdAt', Type\DateType::class)
            ->add('save', Type\SubmitType::class, ['label' => 'Create Task'])
            ->getForm();
        
        return $formBuilder;
    }

    private function getCustomFormBuilder(): FormBuilderInterface
    {
        return $this->customForm->createBuilder(Type\DateType::class);
    }
}