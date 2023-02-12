<?php

namespace App\Form;

use App\Entity\Adherent;
use App\Entity\WorkIncident;
use App\Entity\WorkIncidentLesion;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class WorkIncidentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('incidentPlace',TextType::class, [
                'required' =>true,
                
                'label' =>"Lieu d'incident",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Lieu d'incident",
                    'maxlength'=>'180',
                ]
            ])
            ->add('source', TextType::class, [
                'required' =>true,
                
                'label' =>"Agent matériel",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Agent matériel",
                    'maxlength'=>'180',
                ]
            ])
            ->add('reason', EntityType::class,[
                'class'=> WorkIncidentLesion::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->getQueryLesionEnable();
                },
                'choice_label' => 'name',
                'label' => 'Natures de lésion:',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true,
                // 'multiple'=>true,
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
            'data_class' => WorkIncident::class,
        ]);
    }
}
