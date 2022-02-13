<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class GuitarModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazev',
                'required' => true,
            ])
            ->add('titleCs', TextType::class, [
                'label' => 'Hlavička',
                'required' => false,
            ])
            ->add('titleEn', TextType::class, [
                'label' => 'Hlavička',
                'required' => false,
            ])
            ->add('titleDe', TextType::class, [
                'label' => 'Hlavička',
                'required' => false,
            ])
            ->add('metaTitleCs', TextType::class, [
                'label' => 'Meta title',
                'required' => false,
                'constraints' => [new Length(null, 3, 60)],
            ])
            ->add('metaTitleEn', TextType::class, [
                'label' => 'Meta title',
                'required' => false,
                'constraints' => [new Length(null, 3, 60)],
            ])
            ->add('metaTitleDe', TextType::class, [
                'label' => 'Meta title',
                'required' => false,
                'constraints' => [new Length(null, 3, 60)],
            ])
            ->add('metaDescriptionCs', TextType::class, [
                'label' => 'Meta description',
                'required' => false,
                'constraints' => [new Length(null, 0, 255)],
            ])
            ->add('metaDescriptionEn', TextType::class, [
                'label' => 'Meta description',
                'required' => false,
                'constraints' => [new Length(null, 0, 255)],
            ])
            ->add('metaDescriptionDe', TextType::class, [
                'label' => 'Meta description',
                'required' => false,
                'constraints' => [new Length(null, 0, 255)],
            ])
            ->add('descriptionCs', TextareaType::class, [
                'label' => 'Krátký popis',
                'required' => false,
            ])
            ->add('descriptionEn', TextareaType::class, [
                'label' => 'Krátký popis',
                'required' => false,
            ])
            ->add('descriptionDe', TextareaType::class, [
                'label' => 'Krátký popis',
                'required' => false,
            ])
            ->add('specsCs', CKEditorType::class, [
                'label' => 'Specifikace',
                'required' => false,
            ])
            ->add('specsEn', CKEditorType::class, [
                'label' => 'Specifikace',
                'required' => false,
            ])
            ->add('specsDe', CKEditorType::class, [
                'label' => 'Specifikace',
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