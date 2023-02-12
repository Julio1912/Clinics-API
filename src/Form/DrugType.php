<?php

namespace App\Form;

use App\Entity\Drug;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DrugType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // dd($options["data"]);
        if(!$options["data"]->getId()){
            $builder
            ->add('name', TextType::class,[
                'required' =>true,
                
                'label' =>"Nom",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Nom Médicament",
                    'maxlength'=>'180',
                ]
            ])
            ->add('quantity', IntegerType::class,[
                'required' =>true,
                
                'label' =>"Quantité(s)",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Quantité(s)",
                    'maxlength'=>'180',
                ]
            ])
            ->add('type',ChoiceType::class, [
                'required'=>true,
                'label'=>"Type",
                'attr'=>[
                    'class'=>'form-control',
                ],
                'choices'=> [
                    "Ampoule"       => "AMP" ,
                    "Comprimé"      =>"CMP",
                    "Flacon"        =>"FLC",
                    "Gellule"       =>"GEL",
                    "Ovule"         =>"OVL",
                    "Sachet"        =>"SCH",
                    "Suppositoire"  =>"SUPP",
                    "Tube"          =>"TUBE",
                    "Unités"        =>"UNIT",
                    "Injection"     =>"INJ",
                    // "Sirop"      =>"SRP"
                ]
            ])
            ;
        }else{


            $builder
            ->add('name', TextType::class,[
                'required' =>true,
                
                'label' =>"Nom",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Nom Médicament",
                    'maxlength'=>'180',
                ]
            ])
            ->add('quantity', IntegerType::class,[
                'required' =>true,
                'data' => 0,
                'label' =>"Quantité(s)",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Quantité(s)",
                    'maxlength'=>'180',
                ]
            ])
            ->add('type',ChoiceType::class, [
                'required'=>true,
                'label'=>"Type",
                'attr'=>[
                    'class'=>'form-control',
                ],
                'choices'=> [
                    "Ampoule"       => "AMP" ,
                    "Comprimé"      =>"CMP",
                    "Flacon"        =>"FLC",
                    "Gellule"       =>"GEL",
                    "Ovule"         =>"OVL",
                    "Sachet"        =>"SCH",
                    "Suppositoire"  =>"SUPP",
                    "Tube"          =>"TUBE",
                    "Unités"        =>"UNIT",
                    "Injection"     =>"INJ",
                ]
            ])
            ;
        }
        
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Drug::class,
        ]);
    }
}
