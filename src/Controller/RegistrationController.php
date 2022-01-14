<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Services\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/enregistrement", name="app_register", methods={"GET", "POST"})
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $submittedToken = $user->getToken();

            $mailer = new Mailer($mailer);
            $mailer->sendMail($form->get('email')->getData(), $submittedToken, $user);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le compte a bien été créé mais doit encore être validé : regardez vos mails !');

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/verification/{token}", name="app_verify_email", methods={"GET", "POST"})
     */
    public function verifyUserEmail(User $user): Response
    {
        if ($user) {
            $user->setIsVerified(true);
            $user->setToken(null);

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Votre adresse email a bien été vérifiée.');

            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('error', 'Une erreur est survenue. Veuillez réessayer.');

        return $this->redirectToRoute('home');
    }
}
