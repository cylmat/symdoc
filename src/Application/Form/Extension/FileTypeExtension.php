<?php

namespace App\Application\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class FileTypeExtension extends AbstractTypeExtension implements FormTypeExtensionInterface
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $parentData = $form->getData();
        $accessor = PropertyAccess::createPropertyAccessor();
        $view->vars['image_file_fromext'] = 'https://static.vecteezy.com/packs/media/components/' .
            'global/search-explore-nav/img/vectors/term-bg-1-666de2d941529c25aa511dc18d727160.jpg';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['default_file_ext']);
    }

    public static function getExtendedTypes(): iterable
    {
        return [FileType::class]; // ...
    }
}
