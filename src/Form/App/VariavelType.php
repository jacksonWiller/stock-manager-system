<?php

namespace App\Form\App;

use App\Entity\App\Variavel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VariavelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active')
            ->add('order')
            ->add('name')
            ->add('value')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Variavel::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ]);
    }
}
