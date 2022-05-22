<?php

namespace App\Application\Form;

use App\Application\Form\Transformer\IntToObjectTransformer;
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

class UserType extends AbstractType implements FormTypeInterface
{
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
                    'inherit_data' => true, // data transformers are not applied to that field.
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

        $builder->add('intObject', Type\IntegerType::class, ['required' => false]);
        $builder->get('intObject')->addModelTransformer(new IntToObjectTransformer()); // model to normalized

        $builder->get('intObject')->addViewTransformer(new CallbackTransformer( // normalized to view
            function (string $transform) {
                return $transform;
            },
            function (string $reverse) {
                return $reverse;
            }
        ));

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
