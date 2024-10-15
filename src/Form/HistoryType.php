<?php

namespace App\Form;

use App\Entity\History;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('start_date', null, [
                'widget' => 'single_text',
            ])
            ->add('end_date', null, [
                'widget' => 'single_text',
            ])
            ->add('customer')
            ->add('is_back')
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'productName',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => History::class,
        ]);
    }
}
