<?php

namespace App\Form\Pessoa;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Uloc\ApiBundle\Entity\Person\TypeContactPurpose;

class TipoContatoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TypeContactPurpose::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ]);
    }
}
