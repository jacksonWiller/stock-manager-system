<?php

namespace App\Form\Pessoa;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Uloc\ApiBundle\Entity\Person\Contact;
use Uloc\ApiBundle\Entity\Person\ContactPhone;
use Uloc\ApiBundle\Entity\Person\TypeContactPurpose;
use Uloc\ApiBundle\Entity\Person\TypePhonePurpose;

class ContatoExtraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('tag')
            ->add('value')
            ->add('purpose', EntityType::class, [
                'class' => TypeContactPurpose::class
            ])
            ->add('active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ]);
    }
}
