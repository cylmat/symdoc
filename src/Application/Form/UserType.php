<?php

namespace App\Application\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
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
            ->add('username')
            ->add('created_at', Type\DateTimeType::class, [
                'html5' => false,
                'input' => 'datetime_immutable',
                'format' => 'yyyy-MM-dd h:mm:ss', // used with html5 disabled
                'date_format' => 'yyyy-MM-dd',
                'input_format' => 'Ymd H.i.s'
            ])
            ->add('custom_file', Type\FileType::class, [
                'mapped' => false,
                'required' => $options['custom_type_options_file_required'][0], // no server-side validation
                    'label' => 'Custom file info'
            ])
            ->add('save', Type\SubmitType::class, []);

        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                [$this, 'ucfirstUsernameOnPreSetData']
            );
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