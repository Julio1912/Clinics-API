<?php

namespace App\Form;

use App\Entity\ProfessionnalSick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfessionnalSickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('characteristic', TextType::class,[
                'required' =>true,
                
                'label' =>"Caractéristique de la maladie",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Caractérisitique de la maladie",
                    'maxlength'=>'180',
                ]
            ])
            ->add('circumstance', TextType::class, [
                'required' =>true,
                
                'label' =>"Circonstances de survenue",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Circonstances de survenue",
                    'maxlength'=>'180',
                ]
            ])
            ->add('agent', TextType::class, [
                'required' =>true,
                
                'label' =>"Agent de la maladie",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Agent de la maladie",
                    'maxlength'=>'180',
                ]
            ])
            ->add('dayOffNumber', IntegerType::class, [
                'required' =>true,
                
                'label' =>"Nombre de jour d'arrêt",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Nombre de jour d'arrêt",
                    'maxlength'=>'180',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProfessionnalSick::class,
        ]);
    }
}
