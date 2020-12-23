<?php

namespace App\Form\App;

use Symfony\Component\Form\CallbackTransformer;
use Uloc\ApiBundle\Entity\App\GlobalConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GlobalConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('value')
            ->add('description')
            ->add('extra')
        ;

        $builder->get('extra')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    // transform the array to a string
                    return $tagsAsArray;
                },
                function ($tagsAsString) {
                    // transform the string back to an array
                    return json_decode($tagsAsString);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GlobalConfig::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ]);
    }
}
