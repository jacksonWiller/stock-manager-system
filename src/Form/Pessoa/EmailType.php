<?php

namespace App\Form\Pessoa;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Uloc\ApiBundle\Entity\Person\ContactEmail;
use Uloc\ApiBundle\Entity\Person\TypeEmailPurpose;

class EmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('otherPurpose')
            ->add('default')
            ->add('valid')
            ->add('purpose', EntityType::class, [
                'class' => TypeEmailPurpose::class
            ])
            ->add('active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactEmail::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ]);
    }
}
