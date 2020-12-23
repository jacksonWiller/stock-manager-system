<?php

namespace App\Form\Pessoa;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Uloc\ApiBundle\Entity\Person\ContactPhone;
use Uloc\ApiBundle\Entity\Person\TypePhonePurpose;

class TelefoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('areaCode')
            ->add('phoneNumber')
            ->add('otherPurpose')
            ->add('default')
            ->add('cellphone')
            ->add('purpose', EntityType::class, [
                'class' => TypePhonePurpose::class
            ])
            ->add('active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactPhone::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ]);
    }
}
