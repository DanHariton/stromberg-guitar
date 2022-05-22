<?php

namespace App\Form;

use App\Entity\MerchCategory;
use App\Service\EntityTranslator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class MerchType extends AbstractType
{
    private EntityTranslator $entityTranslator;

    public function __construct(EntityTranslator $entityTranslator)
    {
        $this->entityTranslator = $entityTranslator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('merchCategory', EntityType::class, [
                'class' => MerchCategory::class,
                'choice_label' => function (MerchCategory $category) {
                    return $this->entityTranslator->read($category->getName());
                },
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
            ->add('price', TextType::class, [
                'label' => 'Cena',
                'required' => false,
            ])
            ->add('captcha', ReCaptchaType::class, [
                'type' => 'invisible'
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