<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PhysicianType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required'=>true,
                'label'     =>" Nom",
                'attr'      =>[
                    'class' =>'form-control',
                ]
            ])
            ->add('firstname' , TextType::class, [
                'required'  =>true,
                'label'     =>"Prénom",
                'attr'      =>[
                    'class' =>'form-control',
                ]
            ])
            // ->add('email', EmailType::class, [
            //     'required'  => false,
            //     'label'     =>" Email",
            //     'attr'      =>[
            //         'class' =>'form-control',
            //     ]
            // ])
            ->add('password', PasswordType::class, [
                'required'  => false,
                'label'     =>" Mot de passe",
                'attr'      =>[
                    'class' =>'form-control',
                ]
            ])
            ->add('confirm_password' , PasswordType::class,[
                'required'  => false,
                'label'     => "Confirmer mot de passe ",
                'attr'      => [
                    'class' => 'form-control'
                ]])

            ->add('status' , ChoiceType::class , [
                'required'  =>true,
                'label'     => "Activer:",
                'attr'      =>[
                    'class' =>'form-control',
                ],
                'choices'   => [
                    "OUI"   => true,
                    "NON"   => false,
                ],
            ])
            ->add('type_med'  , ChoiceType::class , [
                'required'  =>true,
                'label'     => "Type de poste:",
                'attr'      =>[
                    'class' =>'form-control',
                ],
                'choices'   => [
                    "Medecin"       => 'MED',
                    "Infirmière"    => 'INF',
                    "Stagiaire"     => 'STG',
                ],
            ])
            // ->add('roles')
            // ->add('affiliate')
            // ->add('created')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
