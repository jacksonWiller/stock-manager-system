<?php

namespace App\Form\Pessoa;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Uloc\ApiBundle\Entity\Person\Address;
use Uloc\ApiBundle\Entity\Person\TypeAddressPurpose;

class EnderecoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address')
            ->add('complement')
            ->add('number')
            ->add('district')
            ->add('districtId')
            ->add('zip')
            ->add('city')
            ->add('cityId')
            ->add('state')
            ->add('stateId')
            ->add('otherPurpose')
            ->add('default')
            ->add('latitude')
            ->add('longitude')
            ->add('purpose', EntityType::class, [
                'class' => TypeAddressPurpose::class
            ])
            ->add('active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ]);
    }
}
