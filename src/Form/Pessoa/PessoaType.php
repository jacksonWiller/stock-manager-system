<?php

namespace App\Form\Pessoa;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Uloc\ApiBundle\Entity\Person\Person;

class PessoaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('document')
            ->add('gender', null, [
                'required' => false
            ])
            ->add('birthDate', BirthdayType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'invalid_message' => "Data de nascimento inválida. Formato: dd/MM/yyyy",
                'required' => false,
                'html5' => false
            ])
            ->add('nationality')
            ->add('type')
            ->add('documents', CollectionType::class, [
                'entry_type' => DocumentoType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_extra_fields' => true,
                'csrf_protection' => false
            ])
            ->add('addresses', CollectionType::class, [
                'entry_type' => EnderecoType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_extra_fields' => true,
                'csrf_protection' => false
            ])
            ->add('emails', CollectionType::class, [
                'entry_type' => EmailType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_extra_fields' => true,
                'csrf_protection' => false
            ])
            ->add('phoneNumbers', CollectionType::class, [
                'entry_type' => TelefoneType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_extra_fields' => true,
                'csrf_protection' => false
            ])
            ->add('contacts', CollectionType::class, [
                'entry_type' => ContatoExtraType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_extra_fields' => true,
                'csrf_protection' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'validation_groups' => ['Default', 'personBasic']
        ]);
    }
}
