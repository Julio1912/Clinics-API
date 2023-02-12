<?php

namespace App\Form;

use App\Entity\Diagnostic;
use App\Entity\Prescription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'required'=>true,
                'label'  => "Type de consultation",
                'attr'=>[
                    'class'=>'form-control',
                ],
                'choices'  => [
                    "Consultaion Simple" => "0",
                    "Maladie Professionnelle" => "1",
                    // "Accident de Travail" => "2",
                    "Activité de dentisterie" => "3",
                ],
            ])
            ->add('diagnostic', EntityType::class,[
                'class' => Diagnostic::class,
                'placeholder'=>"Le diagnostique",
                'choice_label'=>'name',
                'label'=>"Diagnostique",
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true
            ])
            ->add('drugName', TextType::class, [
                'required' => true,
                'label'    => "Médicament",
                
                'attr'     =>[
                    'placeholder' => 'Médicament',
                    'class' => 'form-control',
                    'maxlength' => '180'
                ]
            ])
            ->add('numberOfDayPrescribed', IntegerType::class,[
                'required' =>true,
                
                'label' =>"Nombre de(s) Jours de prise",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Nombre de(s) Jours de prise",
                    'maxlength'=>'180',
                ]
            ])
            ->add('morning', IntegerType::class,[
                'required' =>true,
                
                'label' =>"Matin",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Matin",
                    'maxlength'=>'180',
                ]
            ])
            ->add('noon', IntegerType::class,[
                'required' =>true,
                
                'label' =>"Midi",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Midi",
                    'maxlength'=>'180',
                ]
            ])
            ->add('evening', IntegerType::class,[
                'required' =>true,
                
                'label' =>"Soir",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Soir",
                    'maxlength'=>'180',
                ]
            ])
            ->add('sum', IntegerType::class,[
                'required' =>true,
                
                'label' =>"Total",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Total",
                    'maxlength'=>'180',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prescription::class,
        ]);
    }
}
