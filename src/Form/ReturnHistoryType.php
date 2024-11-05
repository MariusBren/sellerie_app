<?php

namespace App\Form;

use App\Entity\History;
use App\Entity\Product;
use App\Entity\Condition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReturnHistoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('condition_state', EntityType::class, [
                'class' => Condition::class,
                'choice_label' => 'conditionName',
                'mapped' => false,
                'label' => 'Condition du produit'
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
