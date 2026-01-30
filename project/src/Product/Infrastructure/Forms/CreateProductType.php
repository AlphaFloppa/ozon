<?php

namespace App\Product\Infrastructure\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CreateProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add(
                'price', 
                MoneyType::class,
                [
                    'currency' => 'RUB',
                    'grouping' => true
                ]
            )
            ->add('image', FileType::class)
            ->add('submit', SubmitType::class);
    }
}