<?php

namespace App\Form;

use App\Entity\Worker;
use App\Entity\Position;
use App\Entity\Establishment;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\FormInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormInterface as FormFormInterface;

class WorkerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class,[
                'required' =>true,
                
                'label' =>"Prénoms",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Prénoms",
                    'maxlength'=>'180',
                ]
            ])
            ->add('lastname', TextType::class,[
                'required' =>true,
                
                'label' =>"Nom",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Nom",
                    'maxlength'=>'180',
                ]
            ])
            // ->add('affiliateNumber')
            ->add('birthDay', DateTimeType::class,[
                'required'=>true,
                'widget' => 'single_text',
                
                'label'=>"Date de naissance",
                'attr' =>[
                    'class'=>'form-control',
                    'maxlength'=>'180',
                ]
            ])
            ->add('gender', ChoiceType::class,[
                'required'=>true,
                'label'=>"Sexe",
                'attr'=>[
                    'class'=>'form-control',
                ],
                'choices'=> [
                    "Homme"=>"homme",
                    "Femme"=>"femme"
                ]
            ])
            ->add('address', TextType::class,[
                'required' =>true,
                
                'label' =>"Adresse",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"Adresse",
                    'maxlength'=>'180',
                ]
            ])
            ->add('phone', TextType::class,[
                'required' =>true,
                
                'label' =>"N° Téléphone",
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>"N° Téléphone",
                    'maxlength'=>'180',
                ]
            ])
            ->add('establishment', EntityType::class,[
                'class' => Establishment::class,
                'placeholder'=>"Choisissez l'établissement",
                'choice_label'=>'name',
                'label'=>"Etablissement",
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true
            ])
            ->add('avatarFile', VichFileType::class ,[
                // 'mapped' => false ,
                'required' => false,
                'label' => 'Photos du travailleur',
                'label_attr' => [
                    'class' => '',
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
        $formModifier = function (FormFormInterface $form, Establishment $establishment = null){
            $positions = null === $establishment ? [] : $establishment->getPosition();
            $adherents = null === $establishment ? [] : $establishment->getAdherents();
            $form->add('position', EntityType::class, [
                'class' => Position::class,
                'choices' => $positions,
                'placeholder'=>"Choisissez le poste",
                'label' => 'Poste',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            // ->add('adherent', EntityType::class,[
            //     'class' => Adherent::class,
            //     'placeholder'=>"Choisissez le travailleur",
            //     'choice_label' => 'firstname',
            //     'choices' => $adherents,
            //     'disabled' => true,
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            // ])
            ;
        };
        
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $form = $event->getForm();
                
                $formModifier($event->getForm(), $data->getEstablishment());
            }
        );
        $builder->get('establishment')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier){
                $establishment = $event->getForm()->getData();
                
                $formModifier($event->getForm()->getParent(), $establishment);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Worker::class,
        ]);
    }
}
