<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                ],
            ])
            ->add('roles' , ChoiceType::class, [
                'choices' => [
                    'utilisateur' => 'ROLE_USER',
                    'admin' => 'ROLE_ADMIN'
                ],
                'multiple' => true, 
                'expanded' => false,
                'attr' => array('data-multi-select-plugin' => 'data-multi-select-plugin')
            ])
            ->add('password', null, [
                'label' => 'Password',
                'attr' => [
                    'placeholder' => 'Password',
                ],
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
