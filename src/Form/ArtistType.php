<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Jméno a příjmení',
                'required' => true,
            ])
            ->add('guitar', TextType::class, [
                'label' => 'Kytara, co používá',
                'required' => true,
            ])
            ->add('link', TextType::class, [
                'label' => 'Odkaz na umělce (web nebo sociální síť)',
                'required' => false,
            ])
            ->add('titleCs', TextType::class, [
                'label' => 'Nadpis',
                'required' => false,
            ])
            ->add('titleEn', TextType::class, [
                'label' => 'Nadpis',
                'required' => false,
            ])
            ->add('titleDe', TextType::class, [
                'label' => 'Nadpis',
                'required' => false,
            ])
            ->add('aboutCs', CKEditorType::class, [
                'label' => 'O umělci',
                'required' => false,
            ])
            ->add('aboutEn', CKEditorType::class, [
                'label' => 'O umělci',
                'required' => false,
            ])
            ->add('aboutDe', CKEditorType::class, [
                'label' => 'O umělci',
                'required' => false,
            ])
            ->add('image1', FileType::class, [
                'label' => 'Obrázek 1 (5Mb max)',
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
            ->add('image2', FileType::class, [
                'label' => 'Obrázek 2 (5Mb max)',
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
            ->add('image3', FileType::class, [
                'label' => 'Obrázek 3 (5Mb max)',
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