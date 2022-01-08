<?php

namespace App\Form;

use App\Entity\MerchCategory;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class MerchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => MerchCategory::class,
                'choice_label' => 'name'
            ])
            ->add('nameCs', TextType::class, [
                'label' => 'Nazev',
                'required' => false,
            ])
            ->add('nameEn', TextType::class, [
                'label' => 'Nazev',
                'required' => false,
            ])
            ->add('nameDe', TextType::class, [
                'label' => 'Nazev',
                'required' => false,
            ])
            ->add('price', CKEditorType::class, [
                'label' => 'Cena',
                'required' => false,
            ])
            ->add('image', FileType::class, [
                'label' => 'Obrázek (5Mb max)',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Nahrajte obrázek (max size 5MB)!',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Uložit',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }
}