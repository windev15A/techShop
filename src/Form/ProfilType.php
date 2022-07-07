<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'required' => false
            ]) 
            ->add('prenom',TextType::class,[
                'required' => false
            ])
            ->add('email', EmailType::class,[
                'required' => false
            ])
            ->add('adresse',TextType::class,[
                'required' => false
            ])
            ->add('codePostal',TextType::class,[
                'required' => false
            ])
            ->add('ville',TextType::class,[
                'required' => false
            ])
            ->add('numeroPortable',TextType::class,[
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
