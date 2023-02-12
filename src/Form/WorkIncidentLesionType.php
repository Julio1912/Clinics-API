<?php

namespace App\Form;

use App\Entity\WorkIncidentLesion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class WorkIncidentLesionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name' , TextType::class,[
                'required' =>true,
                
                'label' =>"Nom",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Nom",
                ]
            ])
            ->add('description' , TextareaType::class,[
                'required' =>true,
                
                'label' =>"Déscription",
                'attr' =>[
                    'class'=>'form-control',
                ]
            ])
            ->add('status' , ChoiceType::class , [
                'required'=>true,
                'label'  => "Activer:",
                'attr'=>[
                    'class'=>'form-control',
                ],
                'choices'  => [
                    "OUI" => true,
                    "NON" => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkIncidentLesion::class,
        ]);
    }
}
