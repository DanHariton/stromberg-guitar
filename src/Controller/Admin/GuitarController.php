<?php

namespace App\Controller\Admin;

use App\Entity\GuitarModel;
use App\Entity\GuitarVariant;
use App\Form\GuitarModelType;
use App\Form\GuitarVariantType;
use App\Repository\GuitarModelRepository;
use App\Service\EntityTranslator;
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
            $em->persist($variant);
            $em->flush();
            $this->addFlash('success', 'Nový varianta úspěšně vytvořen');
            return $this->redirectToRoute('_guitar_model_edit', ['model' => $model->getId()]);
        }

        return $this->render('admin/actions/guitar/model_edit.html.twig', [
            'form' => $form->createView(),
            'variantForm' => $variantType->createView(),
            'guitar'=> $model,
        ]);
    }

    /**
     * @Route("/variant/delete/{model}/{variant}", name="_guitar_model_delete_variant")
     */
    public function deleteVariant(GuitarModel $model, GuitarVariant $variant, EntityManagerInterface $em)
    {
        $model->removeVariant($variant);
        $em->remove($variant);
        $em->flush();
        $this->addFlash('success', 'Varianta byla úspěšně odstraněna');
        return $this->redirectToRoute('_guitar_model_edit', ['model' => $model->getId()]);
    }

//    /**
//     * @Route("/color/add/{model}/{variant}", name="_guitar_model_delete_variant")
//     */
//    public function colorAdd(GuitarModel $model, GuitarVariant $variant, Request $request, EntityManagerInterface $em)
//    {
//        $form = $this->createForm(GuitarColorType::class);
//    }
}