<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

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
            ])
            ->add('titleEn', TextType::class, [
                'label' => 'Název',
                'required' => false,
            ])
            ->add('titleDe', TextType::class, [
                'label' => 'Název',
                'required' => false,
            ])
            ->add('previewCs', TextType::class, [
                'label' => 'Stručný popis',
                'required' => false,
            ])
            ->add('previewEn', TextType::class, [
                'label' => 'Stručný popis',
                'required' => false,
            ])
            ->add('previewDe', TextType::class, [
                'label' => 'Stručný popis',
                'required' => false,
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
            ])
            ->add('metaTitleEn', TextType::class, [
                'label' => 'Meta title',
                'required' => false,
            ])
            ->add('metaTitleDe', TextType::class, [
                'label' => 'Meta title',
                'required' => false,
            ])
            ->add('metaDescriptionCs', TextType::class, [
                'label' => 'Meta description',
                'required' => false,
            ])
            ->add('metaDescriptionEn', TextType::class, [
                'label' => 'Meta description',
                'required' => false,
            ])
            ->add('metaDescriptionDe', TextType::class, [
                'label' => 'Meta description',
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