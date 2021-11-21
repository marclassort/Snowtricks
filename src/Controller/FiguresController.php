<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class FiguresController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET", "HEAD"})
     */
    public function home()
    {
        return $this->render('figures/home.html.twig');
    }

    /**
     * @Route("/figures", name="figures", methods={"GET", "HEAD"})
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
     * @Route("/figures/nouvelle-figure", name="figures_create", methods={"GET", "HEAD"})
     * @Route("/figures/{id}/editer", name="figures_edit", methods={"GET", "HEAD"})
     */
    public function form(Trick $trick = NULL, Request $request, EntityManagerInterface $manager)
    {
        if (!$trick)
        {
            $trick = new Trick();
        }

        $form = $this->createFormBuilder($trick)
                    ->add('name')
                    ->add('description')
                    ->add('category')
                    ->add('content')
                    ->add('author')
                    ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if (!$trick->getId())
            {
                $trick->setCreationDate(new \DateTime());
            }
            
            $manager->persist($trick);
            $manager->flush();

            return $this->redirectToRoute('figures_show', ['id' => $trick->getId()]);
        }

        return $this->render('figures/create.html.twig', [
            'formTrick' => $form->createView(),
            'editMode' => $trick->getId() != NULL
        ]);
    }

    /**
     * @Route("/figures/{id}", name="figures_show", methods={"GET", "HEAD"})
     */
    public function show(Trick $trick)
    { 
        return $this->render('figures/show.html.twig', [
            'trick' => $trick
        ]);
    }
}
