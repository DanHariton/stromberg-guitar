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
use App\Form\ContactType;
use App\Repository\ArtistRepository;
use App\Repository\GuitarModelRepository;
use App\Service\AppMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/{_locale}", name="site_app_index", defaults={"_locale": "cs"}, requirements={"_locale"="en|cs|de"})
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
     * @Route("/{_locale}/guitars", name="site_app_guitars", defaults={"_locale": "cs"}, requirements={"_locale"="en|cs|de"})
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
     * @Route("/{_locale}/guitar/{model}-{slug}", name="site_app_model", defaults={"_locale": "cs"}, requirements={"_locale"="en|cs|de"})
     */
    public function guitarModel(GuitarModel $model, GuitarModelRepository $guitarModelRepository)
    {
        $guitars = $guitarModelRepository->findAllEnabled();

        return $this->render('site/app/guitar_model.html.twig', [
            'guitars' => $guitars,
            'guitar' => $model,
        ]);
    }

    /**
     * @Route("/{_locale}/guitar/{model}-{slug}/{variant}-{vSlug}", name="site_app_variant", defaults={"_locale": "cs"}, requirements={"_locale"="en|cs|de"})
     */
    public function guitarVariant(GuitarVariant $variant, GuitarModelRepository $guitarModelRepository)
    {
        $guitars = $guitarModelRepository->findAllEnabled();

        return $this->render('site/app/guitar_variant.html.twig', [
            'guitars' => $guitars,
            'guitar' => $variant->getModel(),
            'variant' => $variant,
        ]);
    }

    /**
     * @Route("/{_locale}/guitar/{model}-{slug}/{variant}-{vSlug}/{color}-{cSlug}", name="site_app_color", defaults={"_locale": "cs"}, requirements={"_locale"="en|cs|de"})
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
     * @Route("/{_locale}/about", name="site_app_about", defaults={"_locale": "cs"}, requirements={"_locale"="en|cs|de"})
     */
    public function about()
    {
        return $this->render('site/app/about.html.twig');
    }

    /**
     * @Route("/{_locale}/artists", name="site_app_artists", defaults={"_locale": "cs"}, requirements={"_locale"="en|cs|de"})
     */
    public function artists(ArtistRepository $artistRepository)
    {
        return $this->render('site/app/artists.html.twig', [
            'artists' => $artistRepository->findAll()
        ]);
    }

    /**
     * @Route("/{_locale}/artist/{artist}", name="site_app_artist", defaults={"_locale": "cs"}, requirements={"_locale"="en|cs|de"})
     */
    public function artist(Artist $artist)
    {
        return $this->render('site/app/artist.html.twig', [
            'artist' => $artist
        ]);
    }

    /**
     * @Route("/{_locale}/merch", name="site_app_merch", defaults={"_locale": "cs"}, requirements={"_locale"="en|cs|de"})
     */
    public function merch(EntityManagerInterface $em)
    {
        $categories = $em->getRepository(MerchCategory::class)->findAll();
        return $this->render('site/app/merch.html.twig', [
            'categories' => $categories,
            'merchRepository' => $em->getRepository(Merch::class),
        ]);
    }

    /**
     * @Route("/{_locale}/post/{post}", name="site_app_post", defaults={"_locale": "cs"}, requirements={"_locale"="en|cs|de"})
     */
    public function post(Post $post)
    {
        return $this->render('site/app/post.html.twig', [
            'post' => $post
        ]);
    }
}