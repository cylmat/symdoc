<?php

namespace App\Application\FormCreator;

use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\Form;
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

    public function __construct(FormFactoryInterface $formFactory)
    {
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

    public function setControllerForms(?FormInterface $userForm = null, ?FormBuilderInterface $userFormBuilder = null): self
    {
        $this->form = $userForm;
        $this->formBuilder = $userFormBuilder;

        return $this;
    }

    // Symfony\Component\Form\FormFactory()->create($type, $data, $options);
    private function getForm(): FormInterface
    {
        if (!$this->form) {
            return $this->formFactory->create();
        }

        $userForm = $this->form
            ->add('username', null, [
                'block_prefix' => 'special_field_prefix'
            ])
            ->add('agreeTerms', Type\CheckboxType::class, [
                'mapped' => false
            ]);

        return $userForm;
    }

    // Symfony\Component\Form\FormFactory()->createBuilder(FormType::class, $data, $options)
    private function getFormBuilder(): FormBuilderInterface
    {
        if (!$this->formBuilder) {
            return $this->formFactory->createBuilder();
        }

        $userFormBuilder = $this->formBuilder;
        
        $userFormBuilder
            ->add('username', Type\TextType::class)
            // passing "null" will autoload Type\DateTimeType::class, required and maxlength option
            ->add('createdAt', null, [ 
                'attr' => [],

                // Bootstrap datepicker sample
                // 'widget' => 'single_text',
                // 'attr' => ['class' => 'js-datepicker'],

                // format of the input data
                'input' => 'datetime_immutable', // (default) datetime

                'html5' => false, // need to disable to use "format"
                'format' => 'yyyy-MM-dd', // (default) HTML5_FORMAT = "yyyy-MM-dd'T'HH:mm:ss";
                
                'date_format' => IntlDateFormatter::MEDIUM,
                'date_widget' => 'choice', // "default" choice
                'with_minutes' => false,
            ])
            ->add('save', Type\SubmitType::class, ['label' => 'Saving...'])
        ;

        //$userFormBuilder
        //    ->addEventListener();
        
        return $userFormBuilder;
    }

    private function createCustomFormBuilder(): FormBuilderInterface
    {
        // sample: $this->formFactory->createNamed('my_name', TaskType::class, $task);

        return $this->formFactory->createBuilder(Type\DateType::class);
    }
}