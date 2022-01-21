<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login", methods={"GET", "POST"})
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/mot-de-passe-oublie", name="forgotten_password", methods={"GET", "POST"})
     */
    public function forgottenPassword(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $username = $request->request->get('_username');
        $token = $request->request->get('token');

        $mailer = new Mailer($mailer);

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneByUsername($username);

        if ($username) {
            $mailer->sendNewPassword($user->getEmail(), $token, $user);

            $user->setToken($token);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Veuillez consulter votre boîte de réception pour créer un nouveau mot de passe.');
        }

        return $this->render('login/password_forgotten.html.twig');
    }

    /**
     * @Route("/nouveau-mot-de-passe/{token}", name="reset_password", methods={"GET", "POST"})
     */
    public function resetPassword(User $user, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $token = $request->get('token');

        $newPassword = $request->request->get('_password');

        if ($newPassword) {
            $user->setToken(null);

            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $newPassword
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien changé votre mot de passe.');

            return $this->redirectToRoute('home');
        } else if ($user) {
            return $this->render('login/password_reset.html.twig', [
                'token' => $token,
                'username' => $user->getUsername()
            ]);
        }

        $this->addFlash('error', 'Une erreur est survenue. Veuillez réessayer.');

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/deconnexion", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {
        throw new \Exception('La déconnexion a échoué.');
    }
}
