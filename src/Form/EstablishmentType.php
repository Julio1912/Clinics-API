<?php

namespace App\Form;

use App\Entity\Position;
use App\Entity\Establishment;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EstablishmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required'=>true,
                'label'=>" Nom de l'établissement",
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'180',
                ]
            ])
            // ->add('created')
            ->add('address', TextType::class, [
                'required'=>true,
                'label'=>" Adresse",
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'180',
                ]
            ])
            ->add('phone', TextType::class, [
                'required'=>true,
                'label'=>"Numéros de téléphone",
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'180',
                ]
            ])
            // ->add('code')
            ->add('status', ChoiceType::class, [
                'required'=>true,
                'label'  => "Statut:",
                'attr'=>[
                    'class'=>'form-control',
                ],
                'choices'  => [
                    "Activer" => "1",
                    "Desativer" => "0",
                ],
            ])
            ->add('position', EntityType::class,[
                'class'=> Position::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->getQueryPositionEnable();
                },
                'choice_label' => 'name',
                'label' => 'Poste disponnibles:',
                'attr' => [
                    'class' => 'form-multiple',
                ],
                'required' => true,
                'multiple'=>true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Establishment::class,
        ]);
    }
}
