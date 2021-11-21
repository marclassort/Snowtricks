<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET, "HEAD"})
     */
    public function home()
    {
        return $this->render('figures/home.html.twig');
    }

    /**
     * @Route("/figures", name="figures", methods={"GET, "HEAD"})
     */
    public function index(): Response
    {
        return $this->render('figures/index.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }

    /**
     * @Route("/figures/12", name="figures_show", methods={"GET, "HEAD"})
     */
    public function show()
    {
        return $this->render('figures/show.html.twig');
    }
}
