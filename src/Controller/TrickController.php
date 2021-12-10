<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;

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
    public function home(TrickRepository $repo): Response
    {
        $tricks = $repo->findBy([], ['createdAt' => 'DESC']);

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
    public function form(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($trick->getName());

            $trick->setSlug($slug);

            $this->em->persist($trick);
            $this->em->flush($trick);

            return $this->redirectToRoute('home');
        }

        return $this->render('figures/create.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
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
