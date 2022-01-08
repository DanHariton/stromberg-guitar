<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titleCs', TextType::class, [
                'label' => 'Název',
                'required' => false,
                'constraints' => [new Length(null, 3, 255)],
            ])
            ->add('titleEn', TextType::class, [
                'label' => 'Název',
                'required' => false,
                'constraints' => [new Length(null, 3, 255)],
            ])
            ->add('titleDe', TextType::class, [
                'label' => 'Název',
                'required' => false,
                'constraints' => [new Length(null, 3, 255)],
            ])
            ->add('previewCs', TextareaType::class, [
                'label' => 'Stručný popis',
                'required' => false,
                'constraints' => [new Length(null, 3, 255)],
            ])
            ->add('previewEn', TextareaType::class, [
                'label' => 'Stručný popis',
                'required' => false,
                'constraints' => [new Length(null, 3, 255)],
            ])
            ->add('previewDe', TextareaType::class, [
                'label' => 'Stručný popis',
                'required' => false,
                'constraints' => [new Length(null, 3, 255)],
            ])
            ->add('contentCs', CKEditorType::class, [
                'label' => 'Text',
                'required' => false,
            ])
            ->add('contentEn', CKEditorType::class, [
                'label' => 'Text',
                'required' => false,
            ])
            ->add('contentDe', CKEditorType::class, [
                'label' => 'Text',
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