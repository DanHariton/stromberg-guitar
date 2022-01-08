<?php

namespace App\Controller\Admin;

use App\Repository\GuitarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param GuitarRepository $guitarRepository
     * @return Response
     */
    public function list(GuitarRepository $guitarRepository)
    {
        return $this->render('admin/actions/guitar/list.html.twig', [
            'guitars' => $guitarRepository->findAll()
        ]);
    }
}