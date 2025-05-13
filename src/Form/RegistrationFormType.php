<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Email field
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                ],
            )

            // Password and Confirm Password
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Password',
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                ],
                'invalid_message' => 'Passwords do not match.',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
            ])

            // Phone number (optional)
            ->add('phoneNumber', TextType::class, [
                'label' => 'Phone Number',
                'required' => false,
            ])

            // Instagram username (optional)
            ->add('instagramUsername', TextType::class, [
                'label' => 'Instagram Username',
                'required' => false,
            ]);
    }

}


