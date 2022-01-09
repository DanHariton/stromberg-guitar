<?php

namespace App\Controller\Site;

use App\Entity\Artist;
use App\Entity\Merch;
use App\Entity\MerchCategory;
use App\Entity\Post;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/{_locale}", name="site_app_index", defaults={"_locale": "cs"}, requirements={"_locale"="en|cs|de"})
     */
    public function index(EntityManagerInterface $em)
    {
        $lastPosts = $em->getRepository(Post::class)->findLastPosts(3);
        return $this->render('site/app/index.html.twig', [
            'lastPosts' => $lastPosts
        ]);
    }

    /**
     * @Route("/{_locale}/guitar", name="site_app_guitar", defaults={"_locale": "cs"})
     */
    public function guitar()
    {
        return $this->render('site/app/guitar.html.twig');
    }

    /**
     * @Route("/{_locale}/about", name="site_app_about", defaults={"_locale": "cs"})
     */
    public function about()
    {
        return $this->render('site/app/about.html.twig');
    }

    /**
     * @Route("/{_locale}/artists", name="site_app_artists", defaults={"_locale": "cs"})
     */
    public function artists(ArtistRepository $artistRepository)
    {
        return $this->render('site/app/artists.html.twig', [
            'artists' => $artistRepository->findAll()
        ]);
    }

    /**
     * @Route("/{_locale}/artist/{artist}", name="site_app_artist", defaults={"_locale": "cs"})
     */
    public function artist(Artist $artist)
    {
        return $this->render('site/app/artist.html.twig', [
            'artist' => $artist
        ]);
    }

    /**
     * @Route("/{_locale}/merch", name="site_app_merch", defaults={"_locale": "cs"})
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
     * @Route("/{_locale}/post/{post}", name="site_app_post", defaults={"_locale": "cs"})
     */
    public function post(Post $post)
    {
        return $this->render('site/app/post.html.twig', [
            'post' => $post
        ]);
    }
}