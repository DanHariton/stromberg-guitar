<?php

namespace App\Controller\Admin;

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
     * @Route("/add", name="_guitar_add")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EntityTranslator $entityTranslator
     * @param ImageUploader $fileUploader
     */
    public function add(Request $request, EntityManagerInterface $em, EntityTranslator $entityTranslator,
                        ImageUploader $fileUploader)
    {

    }
}