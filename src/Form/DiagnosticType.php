<?php

namespace App\Form;

use App\Entity\Diagnostic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiagnosticType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label'    => "Nom de diagnostique",
                
                'attr'     =>[
                    'placeholder' => 'Nom de diagnostique',
                    'class' => 'form-control',
                    'maxlength' => '180'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                
                'attr' => [
                    'placeholder' => 'Entrez description ici',
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Diagnostic::class,
        ]);
    }
}
