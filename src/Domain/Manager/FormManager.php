<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

class FormManager implements ManagerInterface
{
    private $form;
    private $formBuilder;

    public function call(): array
    {
        return [
            'form' => $this->getForm(),
            'formBuilder' => $this->getFormBuilder()
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
        
        return $formBuilder;
    }
}