<?php

namespace App\Controller;

use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\TrickType;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Repository\VideoRepository;
use App\Services\Handlers\MediaHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
            $videoPath = $this->getParameter('videos_directory');

            $mediaHandler->deleteImages($request);
            $mediaHandler->manageImages($request, $trick, $form, $imagePath);
            $mediaHandler->manageVideos($request, $trick, $form, $videoPath);

            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($trick->getName());
            $trick->setSlug($slug);

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
    public function edit(Trick $trick, Request $request, ImageRepository $imageRepo, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaHandler = new MediaHandler($slugger);

            $imagePath = $this->getParameter('images_directory');
            $videoPath = $this->getParameter('videos_directory');

            $mediaHandler->deleteImages($request);
            $mediaHandler->manageImages($request, $trick, $form, $imagePath);
            $mediaHandler->manageVideos($request, $trick, $form, $videoPath);

            $trick->setUpdatedAt(new \DateTime());

            $this->em->flush();

            $this->addFlash('success', 'La figure a bien été éditée !');

            return $this->redirectToRoute('figures_edit', array(
                "slug" => $trick->getSlug()
            ));
        }

        $images = $imageRepo->findBy([]);

        return $this->render('figures/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'images' => $images
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
            unlink($this->getParameter('images_directory') . '/' . $image->getName());

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
            unlink($this->getParameter('videos_directory') . '/' . $video->getName());

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
     * @Route("/figures/{slug}", name="figures_show", methods={"GET"})
     */
    public function show(Trick $trick, ImageRepository $imageRepo, VideoRepository $videoRepo): Response
    {
        $images = $imageRepo->findByTrick($trick);
        $videos = $videoRepo->findByTrick($trick);

        $media = array_merge($images, $videos);

        return $this->render('figures/show.html.twig', [
            'trick' => $trick,
            'images' => $images,
            'media' => $media
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
