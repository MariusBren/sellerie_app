<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Location;
use App\Entity\Condition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product_name')
            ->add('description')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'categoryName',
            ])
            ->add('condition_state', EntityType::class, [
                'class' => Condition::class,
                'choice_label' => 'conditionName',
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'section', // créer une fonction anonyme en dessous pour permettre d'afficher aussi la shelf
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
