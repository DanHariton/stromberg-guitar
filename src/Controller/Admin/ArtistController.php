<?php

namespace App\Controller\Admin;

use App\Entity\Artist;
use App\Entity\File;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
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
 * @Route("/artist")
 * Class ArtistController
 * @package App\Controller\Admin
 */
class ArtistController extends AbstractController
{
    /**
     * @Route("/list", name="_artist_list")
     * @param ArtistRepository $artistRepository
     * @return Response
     */
    public function list(ArtistRepository $artistRepository)
    {
        return $this->render('admin/actions/artist/list.html.twig', [
            'artists' => $artistRepository->findAll()
        ]);
    }

    /**
     * @Route("/delete/{artist}", name="_artist_delete")
     * @param Artist $artist
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function delete(Artist $artist, EntityManagerInterface $em)
    {
        foreach ($artist->getFiles() as $file) {
            $em->remove($file);
        }

        $em->remove($artist);
        $em->flush();

        $this->addFlash('success', 'Umělec byl úspěšně smazán');
        return $this->redirectToRoute('_artist_list');
    }

    /**
     * @Route("/edit/{artist}", name="_artist_edit")
     * @param Artist $artist
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param EntityTranslator $entityTranslator
     * @param ImageUploader $fileUploader
     * @return Response
     * @throws Exception
     */
    public function edit(Artist $artist, EntityManagerInterface $em, Request $request, EntityTranslator $entityTranslator, ImageUploader $fileUploader)
    {
        $form = $this->createForm(ArtistType::class, $entityTranslator->unmap($artist, Artist::ARTIST_VARS_LANG, Artist::ARTIST_VARS))
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Artist $artist */
            $artist = $entityTranslator->map($form, $artist, Artist::ARTIST_VARS_LANG, Artist::ARTIST_VARS);
            $artistImgFile = $form->get('image1')->getData();

            if ($artistImgFile) {
                $newFilename = $fileUploader->upload($artistImgFile, ImageUploader::TYPE_1600x900);
                $file = new File();
                $file->setFileName($newFilename);
                $artist->addFile($file);
                $em->persist($file);
            }

            $em->persist($artist);
            $em->flush();
        }

        return $this->render('admin/actions/artist/edit.html.twig', [
            'artist' => $artist,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/delete-image/{file}", name="_artist_delete_image")
     * @param File $file
     * @param EntityManagerInterface $em
     * @param ImageUploader $imageUploader
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteImage(File $file, EntityManagerInterface $em, ImageUploader $imageUploader, Request $request)
    {
        $imageUploader->remove($file->getFileName());
        $em->remove($file);
        $em->flush();

        $this->addFlash('success', 'Umělec byl úspěšně smazán');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/add", name="_artist_add")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param ImageUploader $fileUploader
     * @param EntityTranslator $entityTranslator
     * @return Response
     * @throws Exception
     */
    public function add(EntityManagerInterface $em, Request $request, ImageUploader $fileUploader,
                        EntityTranslator $entityTranslator)
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Artist $artist */
            $artist = $entityTranslator->map($form, $artist, Artist::ARTIST_VARS_LANG, Artist::ARTIST_VARS);
            $artistImgFile1 = $form->get('image1')->getData();
            $artistImgFile2 = $form->get('image2')->getData();
            $artistImgFile3 = $form->get('image3')->getData();


            if ($artistImgFile1) {
                $newFilename1 = $fileUploader->upload($artistImgFile1, ImageUploader::TYPE_1600x900);
                $file1 = new File();
                $file1->setFileName($newFilename1);
                $artist->addFile($file1);
                $em->persist($file1);
            }

            if ($artistImgFile2) {
                $newFilename2 = $fileUploader->upload($artistImgFile2, ImageUploader::TYPE_1600x900);
                $file2 = new File();
                $file2->setFileName($newFilename2);
                $artist->addFile($file2);
                $em->persist($file2);
            }

            if ($artistImgFile3) {
                $newFilename3 = $fileUploader->upload($artistImgFile3, ImageUploader::TYPE_1600x900);
                $file3 = new File();
                $file3->setFileName($newFilename3);
                $artist->addFile($file3);
                $em->persist($file3);
            }

            $em->persist($artist);
            $em->flush();

            return $this->redirectToRoute('_artist_list');
        }

        return $this->render('admin/actions/artist/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}