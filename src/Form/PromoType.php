<?php

namespace App\Form;

use App\Entity\Promo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codePromo', TextType::class,[
                'required' => false,
            ])
            ->add('tauxPromo', TextType::class,[
                'required' => false,
            ])
            ->add('date_debut', DateType::class,[
                'required' => false,
                 'widget' => 'single_text',
                 'html5' => true
            ] )
            ->add('date_fin', DateType::class, [
                'required' => false,
                 'widget' => 'single_text',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promo::class,
        ]);
    }
}
