

<?php


use App\Entity\Category;
use App\Entity\Fabricant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q',TextType::class,
            [
                "required" => false,
                "label" => 'Nom du produit'
            ])
            ->add('min',TextType::class,
            [
                "required" => false
            ])
            ->add('max',TextType::class,
            [
                "required" => false
            ])
            ->add('categories', EntityType::class, [
                "required" => false,
                "class" => Category::class,
                "choice_label" => "libelle",
                "multiple" => true,
                'expanded' => true
            ])
            ->add('fabricants', EntityType::class, [
                "required" => false,
                "class" => Fabricant::class,
                "choice_label" => "nom",
                "multiple" => true,
                'expanded' => true
            ])
            ->add('order', ChoiceType::class,[
                "label" => "Trier par : ",
                "choices" => [
                    'Prix croissant ' => 1,
                    'Prix décroissant ' => 2,
                    'Nom croissant ' => 3,
                    'Nom décroissant ' => 4,
                ]
            ])
            
            
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
        ]);
    }
}
