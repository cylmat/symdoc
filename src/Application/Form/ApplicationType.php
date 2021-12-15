<?php

namespace App\Application\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationType extends FormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('dt', DateTimeType::class, [
                'html5' => false,
                'format' => 'yyyy-MM-dd h:mm:ss', // used with html5 disabled
                'date_format' => 'yyyy-MM-dd',
                'input_format' => 'Ymd H.i.s'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);
    }

    public function getParent()
    {
        return null;
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}