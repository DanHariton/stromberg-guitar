<?php

namespace App\Controller\Admin;

use App\Entity\File;
use App\Entity\GuitarColor;
use App\Entity\GuitarModel;
use App\Entity\GuitarVariant;
use App\Form\GuitarColorAddType;
use App\Form\GuitarColorEditType;
use App\Form\GuitarColorType;
use App\Form\GuitarModelType;
use App\Form\GuitarVariantRenameType;
use App\Form\GuitarVariantType;
use App\Repository\GuitarModelRepository;
use App\Service\EntityTranslator;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GuitarController
 * @package App\Controller\Admin
 * @Route("/guitar")
 */
class GuitarController extends AbstractController
{
    /**
     * @Route("/list", name="_guitar_list")
     * @param GuitarModelRepository $guitarModelRepository
     * @return Response
     */
    public function list(GuitarModelRepository $guitarModelRepository)
    {
        return $this->render('admin/actions/guitar/list.html.twig', [
            'guitars' => $guitarModelRepository->findAll()
        ]);
    }

    /**
     * @Route("/model/add", name="_guitar_model_add")
     */
    public function addModel(Request $request, EntityManagerInterface $em, EntityTranslator $entityTranslator)
    {
        $form = $this->createForm(GuitarModelType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $model = new GuitarModel();
            /** @var GuitarModel $model */
            $model = $entityTranslator->map($form, $model, GuitarModel::VARS_LANG, GuitarModel::VARS);
            $model->setEnabled(false);

            $em->persist($model);
            $em->flush();
            $this->addFlash('success', 'Nový model úspěšně vytvořen');
            return $this->redirectToRoute('_guitar_model_edit', ['model' => $model->getId()]);
        }

        return $this->render('admin/actions/guitar/model_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/model/toggle/{model}", name="_guitar_toggle")
     */
    public function toggle(GuitarModel $model, EntityManagerInterface $em)
    {
        $model->setEnabled(!$model->getEnabled());
        $em->flush();
        return $this->redirectToRoute('_guitar_list');
    }

    /**
     * @Route("/model/edit/{model}", name="_guitar_model_edit")
     */
    public function editModel(GuitarModel $model, Request $request, EntityManagerInterface $em, EntityTranslator $entityTranslator)
    {
        $form = $this->createForm(GuitarModelType::class, $entityTranslator->unmap($model, GuitarModel::VARS_LANG, GuitarModel::VARS))
            ->handleRequest($request);

        $variantType = $this->createForm(GuitarVariantType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var GuitarModel $model */
            $model = $entityTranslator->map($form, $model, GuitarModel::VARS_LANG, GuitarModel::VARS);
            $em->flush();
            $this->addFlash('success', 'Nový model úspěšně upraven');
            return $this->redirectToRoute('_guitar_model_edit', ['model' => $model->getId()]);
        }

        if ($variantType->isSubmitted() && $variantType->isValid()) {
            $variant = new GuitarVariant();
            /** @var GuitarVariant $variant */
            $variant = $entityTranslator->map($variantType, $variant, GuitarVariant::VARS_LANG, GuitarVariant::VARS);
            $variant->setModel($model);
            $variant->setIsDefault(false);
            $em->persist($variant);
            $em->flush();
            $this->addFlash('success', 'Nový varianta úspěšně vytvořen');
            return $this->redirectToRoute('_guitar_model_edit', ['model' => $model->getId()]);
        }

        return $this->render('admin/actions/guitar/model_edit.html.twig', [
            'form' => $form->createView(),
            'variantForm' => $variantType->createView(),
            'guitar' => $model,
        ]);
    }

    /**
     * @Route("/model/delete/{model}", name="_guitar_delete_model")
     */
    public function deleteModel(GuitarModel $model, EntityManagerInterface $em, ImageUploader $imageUploader)
    {
        foreach ($model->getVariants() as $variant) {
            $this->deleteVariant($model, $variant, $em, $imageUploader);
        }
        $em->remove($model);
        $em->flush();
        $this->addFlash('success', 'Model byla úspěšně odstraněna');
        return $this->redirectToRoute('_guitar_list');
    }

    /**
     * @Route("/variant/rename/{variant}", name="_guitar_model_rename_variant")
     */
    public function renameVariant(GuitarVariant $variant, EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(GuitarVariantRenameType::class, $variant)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Změny byly úspěšně uloženy');
            return $this->redirectToRoute('_guitar_model_edit', ['model' => $variant->getModel()->getId()]);
        }

        return $this->render('admin/actions/guitar/variant_rename.html.twig', [
            'form' => $form->createView(),
            'guitar' => $variant->getModel(),
        ]);
    }

    /**
     * @Route("/variant/delete/{model}/{variant}", name="_guitar_model_delete_variant")
     */
    public function deleteVariant(GuitarModel $model, GuitarVariant $variant, EntityManagerInterface $em, ImageUploader $imageUploader)
    {
        foreach ($variant->getColors() as $color) {
            $this->colorDelete($model, $color, $em, $imageUploader);
        }

        $model->removeVariant($variant);
        $em->remove($variant);
        $em->flush();
        $this->addFlash('success', 'Varianta byla úspěšně odstraněna');
        return $this->redirectToRoute('_guitar_model_edit', ['model' => $model->getId()]);
    }

    /**
     * @Route("/color/add/{model}/{variant}", name="_guitar_color_add")
     */
    public function colorAdd(GuitarModel $model, GuitarVariant $variant, Request $request, EntityManagerInterface $em, ImageUploader $imageUploader)
    {
        $color = new GuitarColor();
        $color->setVariant($variant);
        $color->setIsDefault(false);
        $form = $this->createForm(GuitarColorType::class, $color)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($color);

            $images = $form->get('files')->getData();
            if (!empty($images)) {
                foreach ($images as $order => $image) {
                    $imageName = $imageUploader->upload($image, ImageUploader::TYPE_1000x1144);
                    $imageFile = new File();
                    $imageFile->setFileName($imageName);
                    $imageFile->setGuitarColor($color);
                    $imageFile->setOrder($order);
                    $color->addImage($imageFile);
                    $em->persist($imageFile);
                }
            }

            $em->flush();
            $this->addFlash('success', 'Barva byla úspěšně přidána');
            return $this->redirectToRoute('_guitar_model_edit', ['model' => $model->getId()]);
        }

        return $this->render('admin/actions/guitar/color_add.html.twig', [
            'form' => $form->createView(),
            'guitar' => $model,
            'variant' => $variant,
        ]);
    }

    /**
     * @Route("/color/delete/{model}/{color}", name="_guitar_color_delete")
     */
    public function colorDelete(GuitarModel $model, GuitarColor $color, EntityManagerInterface $em, ImageUploader $imageUploader)
    {
        $images = $color->getImages();
        foreach ($images as $image) {
            $imageUploader->remove($image->getFileName());
            $em->remove($image);
        }
        $em->remove($color);
        $em->flush();
        $this->addFlash('success', 'Barva byla úspěšně odstraněna');
        return $this->redirectToRoute('_guitar_model_edit', ['model' => $model->getId()]);
    }

    /**
     * @Route("/color/edit/{color}", name="_guitar_color_edit")
     */
    public function colorEdit(GuitarColor $color, Request $request, EntityManagerInterface $em, ImageUploader $imageUploader)
    {
        $form = $this->createForm(GuitarColorEditType::class, $color)->handleRequest($request);
        $fileForm = $this->createForm(GuitarColorAddType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Barva byla úspěšně změněna');
            return $this->redirectToRoute('_guitar_color_edit', ['color' => $color->getId()]);
        }

        if ($fileForm->isSubmitted() && $fileForm->isValid()) {
            $maxOrder = $color->getMaxOrder();
            $images = $fileForm->get('files')->getData();
            if (!empty($images)) {
                foreach ($images as $order => $image) {
                    $imageName = $imageUploader->upload($image, ImageUploader::TYPE_1000x1144);
                    $imageFile = new File();
                    $imageFile->setFileName($imageName);
                    $imageFile->setGuitarColor($color);
                    $imageFile->setOrder($maxOrder + $order);
                    $color->addImage($imageFile);
                    $em->persist($imageFile);
                }
            }
            $em->flush();
            $this->addFlash('success', 'Barva byla úspěšně změněna');
            return $this->redirectToRoute('_guitar_color_edit', ['color' => $color->getId()]);
        }

        return $this->render('admin/actions/guitar/color_edit.html.twig', [
            'form' => $form->createView(),
            'fileForm' => $fileForm->createView(),
            'color' => $color,
            'guitar' => $color->getVariant()->getModel(),
            'variant' => $color->getVariant(),
        ]);
    }

    /**
     * @Route("/color/image/delete/{color}/{file}", name="_guitar_color_image_delete")
     */
    public function colorImageDelete(GuitarColor $color, File $file, EntityManagerInterface $em, ImageUploader $imageUploader)
    {
        $imageUploader->remove($file->getFileName());
        $em->remove($file);
        $em->flush();
        $this->addFlash('success', 'Barva byla úspěšně změněna');
        return $this->redirectToRoute('_guitar_color_edit', ['color' => $color->getId()]);
    }

    /**
     * @Route("/color/image/reorder/{color}/{file}/{way}", name="_guitar_color_image_reorder")
     */
    public function colorImageReorder($way, GuitarColor $color, File $file, EntityManagerInterface $em)
    {
        $color->reorder($file, $way === 'top' ? 1 : -1);
        $em->flush();
        $this->addFlash('success', 'Barva byla úspěšně změněna');
        return $this->redirectToRoute('_guitar_color_edit', ['color' => $color->getId()]);
    }

    /**
     * @Route("/color/default/{color}", name="_guitar_color_set_default")
     */
    public function colorSetDefault(GuitarColor $color, EntityManagerInterface $em)
    {
        $model = $color->getVariant()->getModel();

        /** @var GuitarVariant[] $variants */
        $variants = $model->getVariants()->getValues();
        foreach ($variants as $variant) {
            $variant->setIsDefault(false);
            /** @var GuitarColor[] $variantColors */
            $variantColors = $variant->getColors();
            foreach ($variantColors as $variantColor) {
                $variantColor->setIsDefault(false);
            }
        }

        $color->setIsDefault(true);
        $color->getVariant()->setIsDefault(true);
        $em->flush();
        $this->addFlash('success', 'Výchozí barva byla úspěšně změněna');
        return $this->redirectToRoute('_guitar_model_edit', ['model' => $model->getId()]);
    }
}