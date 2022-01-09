<?php

namespace App\Controller\Admin;

use App\Entity\File;
use App\Entity\Merch;
use App\Entity\MerchCategory;
use App\Entity\Post;
use App\Form\MerchCategoryType;
use App\Form\MerchType;
use App\Form\PostType;
use App\Repository\MerchCategoryRepository;
use App\Repository\MerchRepository;
use App\Service\EntityTranslator;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MerchController
 * @package App\Controller\Admin
 * @Route("/merch")
 */
class MerchController extends AbstractController
{
    /**
     * @Route("/category/list", name="_merch_category_list")
     * @param MerchCategoryRepository $merchCategoryRepository
     * @return Response
     */
    public function categoryList(MerchCategoryRepository $merchCategoryRepository)
    {
        return $this->render('admin/actions/merch/merchCategory/list.html.twig', [
            'merchCategories' => $merchCategoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/category/add", name="_merch_category_add")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param EntityTranslator $entityTranslator
     * @return RedirectResponse|Response
     */
    public function categoryAdd(EntityManagerInterface $em, Request $request, EntityTranslator $entityTranslator)
    {
        $merchCategory = new MerchCategory();
        $form = $this->createForm(MerchCategoryType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var MerchCategory $merchCategory */
            $merchCategory = $entityTranslator->map($form, $merchCategory, MerchCategory::MERCH_CATEGORY_VARS_LANG);

            $em->persist($merchCategory);
            $em->flush();

            $this->addFlash('success', 'Kategorie zboží(' . $merchCategory->getId() . ') byla úspěšně přidána');
            return $this->redirectToRoute("_merch_category_list");
        }

        return $this->render("admin/actions/merch/merchCategory/add.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/edit/{merchCategory}", name="_merch_category_edit")
     * @param MerchCategory $merchCategory
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param EntityTranslator $entityTranslator
     * @return RedirectResponse|Response
     */
    public function categoryEdit(MerchCategory $merchCategory, EntityManagerInterface $em, Request $request,
                                 EntityTranslator $entityTranslator)
    {

        $form = $this->createForm(MerchCategoryType::class, $entityTranslator->unmap($merchCategory, MerchCategory::MERCH_CATEGORY_VARS_LANG))
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var MerchCategory $merchCategory */
            $merchCategory = $entityTranslator->map($form, $merchCategory, MerchCategory::MERCH_CATEGORY_VARS_LANG);

            $em->persist($merchCategory);
            $em->flush();

            $this->addFlash('success', 'Kategorie zboží(' . $merchCategory->getId() . ') byla úspěšně změněna');
            return $this->redirectToRoute("_merch_category_list");
        }

        return $this->render("admin/actions/merch/merchCategory/edit.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/delete/{merchCategory}", name="_merch_category_delete")
     * @param MerchCategory $merchCategory
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function categoryDelete(MerchCategory $merchCategory, EntityManagerInterface $em)
    {
        $em->remove($merchCategory);
        $em->flush();

        $this->addFlash('success', 'Kategorie zboží byla úspěšně smazána');
        return $this->redirectToRoute('_merch_category_list');
    }

    /**
     * @Route("/list", name="_merch_list")
     * @param MerchRepository $merchRepository
     * @return Response
     */
    public function list(MerchRepository $merchRepository)
    {
        return $this->render("admin/actions/merch/list.html.twig", [
            'merchs' => $merchRepository->findAll()
        ]);
    }

    /**
     * @Route("/add", name="_merch_add")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param EntityTranslator $entityTranslator
     * @param ImageUploader $fileUploader
     * @return Response
     * @throws Exception
     */
    public function add(EntityManagerInterface $em, Request $request, EntityTranslator $entityTranslator, ImageUploader $fileUploader)
    {
        $merch = new Merch();
        $form = $this->createForm(MerchType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Merch $merch */
            $merch = $entityTranslator->map($form, $merch, Merch::MERCH_VARS_LANG, Merch::MERCH_VARS);
            $merch->setEnabled(false);
            $merchImgFile = $form->get('image')->getData();

            if ($merchImgFile) {
                $newFilename = $fileUploader->upload($merchImgFile, ImageUploader::TYPE_1000x1144);
                $merch->setImageFilename($newFilename);
            }

            $em->persist($merch);
            $em->flush();

            $this->addFlash('success', 'Zboží(' . $merch->getId() . ') bylo úspěšně přidáno');
            return $this->redirectToRoute("_merch_list");
        }

        return $this->render("admin/actions/merch/add.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{merch}", name="_merch_delete")
     * @param Merch $merch
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function delete(Merch $merch, EntityManagerInterface $em)
    {
        $em->remove($merch);
        $em->flush();

        $this->addFlash('success', 'Zboží bylo úspěšně smazáno');
        return $this->redirectToRoute('_merch_list');
    }

    /**
     * @Route("/edit/{merch}", name="_merch_edit")
     * @param Merch $merch
     * @param EntityTranslator $entityTranslator
     * @param Request $request
     * @param ImageUploader $imageUploader
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function edit(Merch $merch, EntityTranslator $entityTranslator, Request $request,
                         ImageUploader $imageUploader, EntityManagerInterface $em)
    {
        $form = $this->createForm(MerchType::class, $entityTranslator->unmap($merch, Merch::MERCH_VARS_LANG, Merch::MERCH_VARS))
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Merch $merch */
            $merch = $entityTranslator->map($form, $merch, Merch::MERCH_VARS_LANG, Merch::MERCH_VARS);
            $merchImgFile = $form->get('image')->getData();

            if ($merchImgFile) {
                $imageUploader->remove($merch->getImageFilename());
                $newImgFilename = $imageUploader->upload($merchImgFile, ImageUploader::TYPE_1000x1144);
                $merch->setImageFilename($newImgFilename);
            }

            $em->persist($merch);
            $em->flush();

            $this->addFlash('success', 'Zboží(' . $merch->getId() . ') bylo úspěšně změněno');
            return $this->redirectToRoute('_merch_list');
        }

        return $this->render('admin/actions/merch/edit.html.twig', [
            'merch' => $merch,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/toggle/{merch}", name="_merch_toggle")
     * @param Merch $merch
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function toggleVision(Merch $merch, EntityManagerInterface $em)
    {
        $merch->setEnabled(!$merch->getEnabled());
        $em->persist($merch);
        $em->flush();

        $this->addFlash('success', 'Zboží(' . $merch->getId() . ') bylo úspěšně změněno');
        return $this->redirectToRoute('_merch_list');
    }
}