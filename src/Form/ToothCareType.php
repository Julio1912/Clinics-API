<?php

namespace App\Form;

use App\Entity\ToothCare;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ToothCareType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required'=>true,
                'label'=>"Nom ",
                'attr'=>[
                    'placeholder' => 'Nom activité dentisterie',
                    'class'=>'form-control',
                    
                ],
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
            'data_class' => ToothCare::class,
        ]);
    }
}
