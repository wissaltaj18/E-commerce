<?php

namespace App\Form;

use App\DTO\RegistrationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class ,[
    'attr' => ['class' => 'form-control']
    ])
            ->add('email', EmailType::class, [
    'attr' => ['class' => 'form-control']
    ])
            ->add('password', RepeatedType::class, [
    'type' => PasswordType::class,
    'first_options' => [
        'label' => 'Password',
        'attr' => ['class' => 'form-control']
    ],
    'second_options' => [
        'label' => 'Confirm Password',
        'attr' => ['class' => 'form-control']
    ],
]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistrationDTO::class,
        ]);
    }
}