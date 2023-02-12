<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AppresailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('month' , ChoiceType::class,[
                'required'=>true,
                'label'=>false ,
                'attr'=>[
                    'class'=>'form-control',
                ],
                'choices'=> [
                    " Mois  "          =>" Mois " , 
                    "Janvier"   =>"1",
                    "Février"   =>"2",
                    "Mars"      =>"3",
                    "Avril"     =>"4",
                    "Mai"       =>"5",
                    "Juin"      =>"6",
                    "Juillet"   =>"7",
                    "Aout"      =>"8",
                    "Séptembre" =>"9",
                    "Octobre"   =>"10",
                    "Novembre"  =>"11",
                    "Décembre"  =>"12",
                ]
            ])
            ->add('years' , ChoiceType::class,[
                'required'=>true,
                'label'=>false,
                'attr'=>[
                    'class'=>'form-control',
                ],
                'choices'=> [
                    "Année "   =>"Année" , 
                    "2021"=>"2021",
                    "2022"=>"2022",
                    "2023"=>"2023",
                    "2024"=>"2024",
                    "2025"=>"2025",
                    "2026"=>"2026",
                    "2027"=>"2027",
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
