<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Services\Handlers\MediaHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/profil", name="profile", methods={"GET", "POST"})
     */
    public function index(AuthenticationUtils $authenticationUtils, Request $request, SluggerInterface $slugger, UserRepository $userRepo): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(array('username' => $lastUsername));

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaHandler = new MediaHandler($slugger);

            $imagePath = $this->getParameter('images_directory');

            $mediaHandler->managePicture($request, $user, $imagePath);

            $user->setFirstName($user->getFirstName());
            $user->setLastName($user->getLastName());

            $this->em->persist($user);
            $this->em->flush();
        }

        $picture = $userRepo->findOneBy(array('username' => $lastUsername));

        return $this->render('user/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error'         => $error,
            'form'          => $form->createView(),
            'picture'       => $picture->getPicture()
        ]);
    }

    /**
     * @Route("/editer/{id}", name="users_edit", methods={"GET", "POST"})
     */
    public function edit(User $user, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPlainPassword()) {
                $plainPassword = $user->getPlainPassword();
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $this->em->persist($user);
            $this->em->flush($user);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
