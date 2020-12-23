<?php

namespace App\Form\App;

use App\Entity\App\Template;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active')
            ->add('order')
            ->add('nome')
            ->add('codigo')
            ->add('descricao')
            ->add('texto')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Template::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ]);
    }
}
