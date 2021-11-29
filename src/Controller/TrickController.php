<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TrickController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function home(TrickRepository $repo): Response
    {
        $tricks = $repo->findAll();

        return $this->render('figures/home.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("/figures", name="figures", methods={"GET"})
     */
    public function index(TrickRepository $repo): Response
    {
        $tricks = $repo->findAll();

        return $this->render('figures/index.html.twig', [
            'controller_name' => 'FiguresController',
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("/nouvelle-figure", name="figures_create", methods={"GET", "POST"})
     * @Route("/figures/{slug}/editer", name="figures_edit", methods={"GET"})
     */
    public function form(request $request, EntityManagerInterface $manager): Response
    {
        $trick = new Trick();

        

        return $this->render('figures/create.html.twig');
    }

    /**
     * @Route("/figures/{slug}", name="figures_show", methods={"GET"})
     */
    public function show(Trick $trick): Response
    {
        return $this->render('figures/show.html.twig', [
            'trick' => $trick
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
