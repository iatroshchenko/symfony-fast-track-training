<?php

namespace App\Form;

use App\Entity\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Constraints\UniqueForEntity\UniqueForEntityConstraint;

class AdminCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
                'constraints' => [
                    new UniqueForEntityConstraint([
                        'field' => 'username',
                        'entityClass' => Admin::class
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => true
            ])
            ->add('save', SubmitType::class, ['label' => 'save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
