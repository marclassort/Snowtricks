<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use App\Services\Handlers\MediaHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function home(TrickRepository $repo, ImageRepository $imageRepo): Response
    {
        $tricks = $repo->findBy([], ['createdAt' => 'DESC']);
        $images = $imageRepo->findBy([]);

        return $this->render('figures/home.html.twig', [
            'tricks' => $tricks,
            'images' => $images
        ]);
    }

    /**
     * @Route("/nouvelle-figure", name="figures_create", methods={"GET", "POST"})
     */
    public function form(Request $request, SluggerInterface $slugger): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaHandler = new MediaHandler($slugger);

            $imagePath = $this->getParameter('images_directory');

            $mediaHandler->manageImages($request, $trick, $form, $imagePath);
            $videos = $mediaHandler->manageVideos($request, $trick);

            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($trick->getName());
            $trick->setSlug($slug);
            $trick->setAuthor($this->getUser());

            foreach ($videos as $video) {
                $this->em->persist($video);
            }

            $this->em->persist($trick);
            $this->em->flush();

            $this->addFlash('success', 'La figure a bien été créée !');

            return $this->redirectToRoute('home');
        }

        return $this->render('figures/create.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/figures/{slug}/editer", name="figures_edit", methods={"GET", "POST"})
     */
    public function edit(Trick $trick, Request $request, ImageRepository $imageRepo, VideoRepository $videoRepo, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaHandler = new MediaHandler($slugger);

            $imagePath = $this->getParameter('images_directory');

            $mediaHandler->manageImages($request, $trick, $form, $imagePath);
            $videos = $mediaHandler->manageVideos($request, $trick);

            if ($videos != []) {
                foreach ($videos as $video) {
                    $this->em->persist($video);
                }
            }

            $trick->setUpdatedAt(new \DateTime());

            $this->em->flush();

            $this->addFlash('success', 'La figure a bien été éditée !');

            return $this->redirectToRoute('figures_edit', array(
                "slug" => $trick->getSlug()
            ));
        }

        $images = $imageRepo->findByTrick($trick);
        $videos = $videoRepo->findByTrick($trick);

        $media = array_merge($images, $videos);

        return $this->render('figures/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'images' => $images,
            'media' => $media
        ]);
    }

    /**
     * @Route("/supprimer/images/{id}", name="image_delete", methods={"DELETE", "POST", "GET"},)
     */
    public function deleteImage(Request $request, Image $image)
    {
        $token = $request->request->get('token');
        $trick = $request->request->get('trick');

        if ($this->isCsrfTokenValid('delete-item', $token)) {
            $imageLink = $this->getParameter('images_directory') . '/' . $image->getName();

            if (is_writable($imageLink)) {
                unlink($this->getParameter('images_directory') . '/' . $image->getName());
            }

            $this->em->remove($image);
            $this->em->flush();

            $this->addFlash('success', 'L\'image a bien été supprimée !');

            return $this->redirectToRoute('figures_edit', array(
                "slug" => $trick
            ));
        } else {
            return new JsonResponse(['error' => 'Token invalide'], 400);
        }
    }

    /**
     * @Route("/supprimer/videos/{id}", name="video_delete", methods={"DELETE", "POST", "GET"})
     */
    public function deleteVideo(Video $video, Request $request)
    {
        $token = $request->request->get('token');
        $trick = $request->request->get('trick');

        if ($this->isCsrfTokenValid('delete-item', $token)) {
            $videoLink = $this->getParameter('videos_directory') . '/' . $video->getName();

            if (is_writable($videoLink)) {
                unlink($videoLink);
            }

            $this->em->remove($video);
            $this->em->flush();

            $this->addFlash('success', 'La vidéo a bien été supprimée !');

            return $this->redirectToRoute('figures_edit', array(
                "slug" => $trick
            ));
        } else {
            return new JsonResponse(['error' => 'Token invalide'], 400);
        }
    }

    /**
     * @Route("/figures/{slug}/supprimer", name="figures_delete")
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('trick_deletion_' . $trick->getId(), $request->request->get('token'))) {
            $this->em->remove($trick);
            $this->em->flush();

            $this->addFlash('success', 'La figure a bien été supprimée !');
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/figures/{slug}", name="figures_show", methods={"GET", "POST"})
     */
    public function show(Trick $trick, Request $request, ImageRepository $imageRepo, VideoRepository $videoRepo, CommentRepository $commentRepo, UserRepository $userRepo, AuthenticationUtils $authenticationUtils): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $images = $imageRepo->findByTrick($trick);
        $videos = $videoRepo->findByTrick($trick);

        $lastUsername = $authenticationUtils->getLastUsername();
        $user = $userRepo->findOneByUsername($lastUsername);

        $media = array_merge($images, $videos);

        $comments = $commentRepo->findByTrick($trick);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentForm = $request->request->get('comment');

            $comment->setContent($commentForm['content']);
            $comment->setCreatedAt(new \DateTime);
            $comment->setUser($user);

            $trick->addComment($comment);

            $this->em->persist($comment);
            $this->em->flush();

            return $this->redirectToRoute('figures_show', array(
                "slug" => $trick->getSlug()
            ));
        }

        return $this->render('figures/show.html.twig', [
            'trick' => $trick,
            'user' => $user,
            'images' => $images,
            'media' => $media,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/politique-de-confidentialite", name="privacy", methods={"GET"})
     */
    public function privacy(): Response
    {
        return $this->render('figures/privacy.html.twig');
    }
}
