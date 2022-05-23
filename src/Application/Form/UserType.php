<?php

namespace App\Application\Form;

use App\Application\Form\Transformer\IntToObjectTransformer;
use App\Domain\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GroupSequence;

class UserType extends AbstractType implements FormTypeInterface
{
    /**
     * bin/console debug:form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod(Request::METHOD_POST)
            //->add('save', Type\SubmitType::class, []);
            ->add('username', null) // null implies GUESSING doctrine constraint
            ->add('created_at', Type\DateTimeType::class, [
                'html5' => false,
                'input' => 'datetime_immutable',
                'format' => 'yyyy-MM-dd h:mm:ss', // used when html5 disabled
                'date_format' => 'yyyy-MM-dd',
                'input_format' => 'Ymd H.i.s',
            ])
            ->add('custom_file', Type\FileType::class, [
                'mapped' => false,
                'required' => $options['custom_type_options_file_required'][0], // no server-side validation
                'label' => 'Custom file info',

                // Allow to create a "child" Type with shared code
                // @see https://symfony.com/doc/current/form/inherit_data_option.html
                'inherit_data' => true, // data transformers are not applied to that field.

                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
                'validation_groups' => false, //disable validation
            ]);

        /**
         * @see https://symfony.com/doc/5.0/form/data_transformers.html
         *
         * Model <=> Norm <=> View
         *  Model data - If you call Form::getData() or Form::setData()
         *  Norm Data - This is a normalized version of your data
         *  View Data - When you call Form::submit($data)
         */

        $builder->add('tags', Type\TextType::class, ['required' => false]);
        $builder->get('tags')
            ->addModelTransformer(new CallbackTransformer(
                // used to render the field in Twig ( "model data" => "norm data")
                function (?array $transformTags) {
                    return $transformTags ? implode(',', $transformTags) : null;
                },
                // submitted value back into the format used in code ("norm data" => "model data")
                function (?string $reverseTags) {
                    return $reverseTags ? explode(',', $reverseTags) : null;
                }
            ));

        $builder->add('int_object', Type\IntegerType::class, [
            'required' => false,
            'property_path' => 'intObject',
        ]);
        $builder->get('int_object')->addModelTransformer(new IntToObjectTransformer()); // model to normalized

        $builder->get('int_object')->addViewTransformer(new CallbackTransformer( // normalized to view
            function (string $transform) {
                return $transform;
            },
            function (string $reverse) {
                return $reverse;
            }
        ));

        /**
         * Mappers
         *  Transformers change the representation (e.g. from "2016-08-12" to a DateTime instance)
         *  Mappers map data (e.g. an object or array) to form fields
         */

        /**
         * Events listeners
         */
        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                [$this, 'ucfirstUsernameOnPreSetData']
            );

        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $event, string $eventName) {
                $event->getData()['email'] = 'pre'; // as array
            });

        $builder
            ->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event, string $eventName) {
                $event->getData()->setEmail('default email'); // as object
            });
    }

    public function ucfirstUsernameOnPreSetData(PreSetDataEvent $event)
    {
        $username = ucfirst($event->getData()->getUsername());
        $event->getForm()->get('username')->setData($username);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_classs' => User::class,

            # CSRF
            'csrf_protection' => true,
            //'csrf_field_name' => '_tokenazerty',
            //'csrf_token_id'   => 'custom_item',

            # Validation
            // e.g. own_registration group, then all constraints that are not in a group
            'validation_groups' => new GroupSequence(['own_registration', 'Default']),

            // empty
            'empty_data' => function (FormInterface $form) {
                return new User();
            },
        ]);

        $resolver->setDefined('custom_type_options_file_required')
            ->setAllowedTypes('custom_type_options_file_required', ['bool[]']);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);
    }

    public function getBlockPrefix()
    {
        //return "my_typped_block_prefix";
        return parent::getBlockPrefix(); // User
    }

    public function getParent()
    {
        return Type\FormType::class;
    }
}
