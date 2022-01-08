<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\EntityTranslator;
use App\Service\ImageUploader;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller\Admin
 * @Route("/news")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/list", name="_post_list")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function list(PostRepository $postRepository)
    {
        return $this->render('admin/actions/post/list.html.twig', [
            'posts' => $postRepository->findAll()
        ]);
    }

    /**
     * @Route("/delete/{post}", name="_post_delete")
     * @param Post $post
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function delete(Post $post, EntityManagerInterface $em)
    {
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Příspěvek byl úspěšně smazán');
        return $this->redirectToRoute('_post_list');
    }

    /**
     * @Route("/toggle/{post}", name="_post_toggle")
     * @param Post $post
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function toggleVision(Post $post, EntityManagerInterface $em)
    {
        $post->setEnabled(!$post->getEnabled());
        $em->persist($post);
        $em->flush();

        $this->addFlash('success', 'Příspěvek byl úspěšně změněn');
        return $this->redirectToRoute('_post_list');
    }

    /**
     * @Route("/create", name="_post_create")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EntityTranslator $entityTranslator
     * @param ImageUploader $fileUploader
     * @return Response
     * @throws Exception
     */
    public function create(Request $request, EntityManagerInterface $em, EntityTranslator $entityTranslator,
                           ImageUploader $fileUploader)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $entityTranslator->map($form, $post, Post::POST_VARS_LANG);
            $postImgFile = $form->get('image')->getData();

            if ($postImgFile) {
                $newImgFilename = $fileUploader->upload($postImgFile, ImageUploader::TYPE_1280x720);
                $post->setImageFilename($newImgFilename);
            }

            if (!$post->getCreated()) {
                $post->setCreated(new DateTime());
            }

            if (is_null($post->getEnabled())) {
                $post->setEnabled(Post::POST_NON_VISIBLE);
            }

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'příspěvek byl úspěšně vytvořen');
            return $this->redirectToRoute('_post_list');
        }


        return $this->render('admin/actions/post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{post}", name="_post_edit")
     * @param Post $post
     * @param EntityTranslator $entityTranslator
     * @param Request $request
     * @param ImageUploader $fileUploader
     * @param Filesystem $filesystem
     * @param EntityManagerInterface $em
     * @return Response
     * @throws Exception
     */
    public function edit(Post $post, EntityTranslator $entityTranslator, Request $request,
                         ImageUploader $fileUploader, EntityManagerInterface $em)
    {
        $form = $this->createForm(PostType::class, $entityTranslator->unmap($post, Post::POST_VARS_LANG, Post::POST_VARS))
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Post $post */
            $post = $entityTranslator->map($form, $post, Post::POST_VARS_LANG);
            $postImgFile = $form->get('image')->getData();

            if ($postImgFile) {
                $fileUploader->remove($post->getImageFilename());
                $newImgFilename = $fileUploader->upload($postImgFile, ImageUploader::TYPE_1280x720);
                $post->setImageFilename($newImgFilename);
            }

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('_post_list');
        }

        return $this->render('admin/actions/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

}