<?php

namespace App\Application\FormCreator;

use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Intl\DateFormatter\IntlDateFormatter;

class FormCreator
{
    /** @var FormInterface $form */
    private $form;
    /** @var FormBuilderInterface $formBuilder */
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
        $userForm = $this->form
            ->add('agreeTerms', Type\CheckboxType::class, [
                'mapped' => false
            ]);

        return $userForm;
    }

    // Symfony\Component\Form\FormFactory()->createBuilder(FormType::class, $data, $options)
    private function getFormBuilder(): FormBuilderInterface
    {
        $userFormBuilder = $this->formBuilder;
        
        $userFormBuilder
            ->add('username', Type\TextType::class)
            // passing "null" will autoload Type\DateTimeType::class, required and maxlength option
            ->add('createdAt', null, [ 
                'attr' => [],

                // datepicker
                // 'widget' => 'single_text',
                // 'attr' => ['class' => 'js-datepicker'],

                // format of the input data
                'input' => 'datetime', // (default) datetime

                'html5' => false, // need to disable to use "format"
                'format' => 'yyyy-MM-dd', // (default) HTML5_FORMAT = "yyyy-MM-dd'T'HH:mm:ss";
                
                'date_format' => IntlDateFormatter::MEDIUM,
                'date_widget' => 'choice', // "default" choice
                'with_minutes' => false,
            ])
            ->add('save', Type\SubmitType::class, ['label' => 'Saving...'])
        ;

        $userFormBuilder
            ->addEventListener();
        
        return $userFormBuilder;
    }

    private function createCustomFormBuilder(): FormBuilderInterface
    {
        // sample: $this->formFactory->createNamed('my_name', TaskType::class, $task);

        return $this->formFactory->createBuilder(Type\DateType::class);
    }
}