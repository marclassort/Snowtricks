<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login", methods={"GET"})
     */
    public function index(): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        return $this->render('login/index.html.twig', []);
    }
}
