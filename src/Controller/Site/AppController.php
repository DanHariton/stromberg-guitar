<?php

namespace App\Controller\Site;

use App\Entity\Artist;
use App\Entity\GuitarColor;
use App\Entity\GuitarModel;
use App\Entity\GuitarVariant;
use App\Entity\Merch;
use App\Entity\MerchCategory;
use App\Entity\Post;
use App\Entity\SliderImages;
use App\Form\ContactMerchType;
use App\Form\ContactType;
use App\Repository\ArtistRepository;
use App\Repository\GuitarModelRepository;
use App\Service\AppMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="site_app_index")
     */
    public function index(EntityManagerInterface $em, Request $request, AppMailer $mailer)
    {
        $lastPosts = $em->getRepository(Post::class)->findLastPosts(3);
        $sliderImages = $em->getRepository(SliderImages::class)->findByEnabled();
        $contactForm = $this->createForm(ContactType::class)->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contactData = $contactForm->getData();
            $mailer->contactFormSubmit($contactData['name'], $contactData['email'], $contactData['content']);
            return $this->redirectToRoute('site_app_index');
        }

        return $this->render('site/app/index.html.twig', [
            'lastPosts' => $lastPosts,
            'sliderImages' => $sliderImages,
            'contactForm' => $contactForm->createView(),
        ]);
    }

    /**
     * @Route("/guitars", name="site_app_guitars")
     */
    public function guitars(GuitarModelRepository $guitarModelRepository)
    {
        $guitars = $guitarModelRepository->findAllEnabled();

        if (empty($guitars)) {
            return $this->redirectToRoute('site_app_index');
        }

        /** @var GuitarModel $guitar */
        $guitar = $guitars[0];

        $variant = $guitar->getDefaultVariant() ?? ($guitar->getVariants()->getValues()[0] ?? null);

        if (!$variant) {
            return $this->redirectToRoute('site_app_model', ['model' => $guitar->getId(), 'slug' => $guitar->getNameSlug()]);
        }

        $color = $variant->getDefaultColor() ?? ($variant->getColors()->getValues()[0] ?? null);
        if (!$color) {
            return $this->redirectToRoute('site_app_variant', [
                'model' => $guitar->getId(),
                'slug' => $guitar->getNameSlug(),
                'variant' => $variant->getId(),
                'vSlug' => $variant->getNameSlug()]
            );
        }

        return $this->redirectToRoute('site_app_color', [
            'model' => $guitar->getId(),
            'slug' => $guitar->getNameSlug(),
            'variant' => $variant->getId(),
            'vSlug' => $variant->getNameSlug(),
            'color' => $color->getId(),
            'cSlug' => $color->getNameSlug(),
        ]);
    }

    /**
     * @Route("/guitar/{model}-{slug}", name="site_app_model")
     */
    public function guitarModel(GuitarModel $model, GuitarModelRepository $guitarModelRepository)
    {
        $guitars = $guitarModelRepository->findAllEnabled();

        $color = $model->getAnyColor();
        if ($color) {
            return $this->redirectToRoute('site_app_color', $color->getRouteParams());
        }

        return $this->render('site/app/guitar_model.html.twig', [
            'guitars' => $guitars,
            'guitar' => $model,
        ]);
    }

    /**
     * @Route("/guitar/{model}-{slug}/{variant}-{vSlug}", name="site_app_variant")
     */
    public function guitarVariant(GuitarVariant $variant, GuitarModelRepository $guitarModelRepository)
    {
        $guitars = $guitarModelRepository->findAllEnabled();

        $color = $variant->getAnyColor();
        if ($color) {
            return $this->redirectToRoute('site_app_color', $color->getRouteParams());
        }

        return $this->render('site/app/guitar_variant.html.twig', [
            'guitars' => $guitars,
            'guitar' => $variant->getModel(),
            'variant' => $variant,
        ]);
    }

    /**
     * @Route("/guitar/{model}-{slug}/{variant}-{vSlug}/{color}-{cSlug}", name="site_app_color")
     */
    public function guitarColor(GuitarColor $color, GuitarModelRepository $guitarModelRepository)
    {
        $guitars = $guitarModelRepository->findAllEnabled();

        return $this->render('site/app/guitar_color.html.twig', [
            'guitars' => $guitars,
            'guitar' => $color->getVariant()->getModel(),
            'variant' => $color->getVariant(),
            'color' => $color,
        ]);
    }

    /**
     * @Route("/about", name="site_app_about")
     */
    public function about()
    {
        return $this->render('site/app/about.html.twig');
    }

    /**
     * @Route("/artists", name="site_app_artists")
     */
    public function artists(ArtistRepository $artistRepository)
    {
        return $this->render('site/app/artists.html.twig', [
            'artists' => $artistRepository->findAll()
        ]);
    }

    /**
     * @Route("/artist/{artist}", name="site_app_artist")
     */
    public function artist(Artist $artist)
    {
        return $this->render('site/app/artist.html.twig', [
            'artist' => $artist
        ]);
    }

    /**
     * @Route("/merch", name="site_app_merch")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param AppMailer $mailer
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function merch(EntityManagerInterface $em, Request $request, AppMailer $mailer)
    {
        $categories = $em->getRepository(MerchCategory::class)->findAll();
        $contactMerchForm = $this->createForm(ContactMerchType::class)->handleRequest($request);

        if ($contactMerchForm->isSubmitted() && $contactMerchForm->isValid()) {
            $contactMerchData = $contactMerchForm->getData();
            $mailer->contactMerchFormSubmit($contactMerchData['name'], $contactMerchData['email'], $contactMerchData['message']);
            return $this->redirectToRoute('site_app_merch');
        }

        return $this->render('site/app/merch.html.twig', [
            'categories' => $categories,
            'merchRepository' => $em->getRepository(Merch::class),
            'contactMerchForm' => $contactMerchForm->createView(),
        ]);
    }

    /**
     * @Route("/post/{post}", name="site_app_post")
     */
    public function post(Post $post)
    {
        return $this->render('site/app/post.html.twig', [
            'post' => $post
        ]);
    }
}