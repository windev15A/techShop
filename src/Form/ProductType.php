<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Fabricant;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle',TextType::class,
                [
                    "required" => false
                ]
            )
            ->add('description', TextareaType::class, [
                "required" => false,
                'attr' => [
                    'rows' => 6
                ]
            ])
            ->add('prix', TextType::class, [
                "required" => false
            ])
            ->add('image', FileType::class, [
                "required" => false,
                "data_class" => null,
                "empty_data" => ""
            ])
            ->add('category', EntityType::class, [
                "required" => false,
                "class" => Category::class,
                "choice_label" => "libelle"
            ])
            ->add('fabricant', EntityType::class, [
                "required" => false,
                "class" => Fabricant::class,
                "choice_label" => "nom"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
