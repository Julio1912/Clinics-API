<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name' , TextType::class, [
                'required'=>true,
                'label'=>" Nom",
                'attr'=>[
                    'class'=>'form-control',
                ]
            ])
            ->add('firstname' , TextType::class, [
                'required'=>true,
                'label'=>"Prénom",
                'attr'=>[
                    'class'=>'form-control',
                ]
            ])
            ->add('email', EmailType::class, [
                'required'=>true,
                'label'=>" Email",
                'attr'=>[
                    'class'=>'form-control',
                ]
            ])
            ->add('password' , PasswordType::class, [
                'required'=>true,
                'label'=>" Mot de passe",
                'attr'=>[
                    'class'=>'form-control',
                ]
            ])
            // ->add('role')
            // ->add('affiliate')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
