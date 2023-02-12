<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, [
                'required'  => false,
                'label'     =>" Mot de passe",
                'attr'      =>[
                    'class' =>'form-control',
                ]
            ])
            ->add('confirm_password', PasswordType::class,[
                'required'  => false,
                'label'     => "Confirmer mot de passe ",
                'attr'      => [
                    'class' => 'form-control'
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
