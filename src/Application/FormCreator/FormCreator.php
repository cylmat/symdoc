<?php

namespace App\Application\FormCreator;

use Symfony\Component\Form\ChoiceList\LazyChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Intl\DateFormatter\IntlDateFormatter;

class FormCreator
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    // Symfony\Component\Form\FormFactory()->create($type, $data, $options);
    public function updateForm(FormInterface $form): void
    {
        if (!$form) {
            $form = $this->formFactory->create();
        }

        $form
            ->add('username', null, [
                'block_prefix' => 'special_field_prefix'
            ])
            ->add('agreeTerms', Type\CheckboxType::class, [
                'mapped' => false
            ])
            ->add('my_int', Type\IntegerType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('rangefield', Type\RangeType::class, [
                'block_name' => 'rangename',
                'error_bubbling' => true,
                'help' => 'This is a range value',
                'mapped' => false,
                'block_prefix' => 'customrangeblockprefix',
            ])
            ->add('choice', Type\ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'alpha' => (new LazyChoiceList(
                        new CallbackChoiceLoader(function () {
                            return [111]; // keys
                        }), function($key) {
                            return [111 => 'value_111'][$key];
                        }
                    ))->getChoices(),
                    'beta' => ['val1' => 'val1']
                ],
                // sample: 'choice_loader' => new CallbackChoiceLoader
            ]);
    }

    // Symfony\Component\Form\FormFactory()->createBuilder(FormType::class, $data, $options)
    public function updateFormBuilder(FormBuilderInterface $formBuilder): void
    {
        $formBuilder
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
            ])
            ->add('save', Type\SubmitType::class, ['label' => 'Saving...'])
        ;

        $formBuilder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(PreSetDataEvent $event, string $eventName, $dispatcher) {
                
            })
            ->addEventListener(FormEvents::POST_SET_DATA, function(PostSetDataEvent $event, string $eventName, $dispatcher) {
                
            });
    }

    private function onPostSetData(string $e)
    {
        
    }

    public function createCustomFormBuilder(): FormBuilderInterface
    {
        // sample: $this->formFactory->createNamed('my_name', TaskType::class, $task);

        return $this->formFactory->createBuilder(Type\DateType::class);
    }
}