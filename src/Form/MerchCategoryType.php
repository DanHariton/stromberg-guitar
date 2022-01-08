<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MerchCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameCs', TextType::class, [
                'label' => 'Nazev druhu',
                'required' => false,
            ])
            ->add('nameEn', TextType::class, [
                'label' => 'Nazev druhu',
                'required' => false,
            ])
            ->add('nameDe', TextType::class, [
                'label' => 'Nazev druhu',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Uložit',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }
}