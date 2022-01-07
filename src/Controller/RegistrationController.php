<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

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

            $token = $request->request->get('token');

            $email = (new Email())
                ->from('marc.lassort@gmail.com')
                ->to($form->get('email'))
                ->subject('Snowtricks - Confirmation du compte')
                ->text('<p>Bonjour</p><p>Pour valider votre compte sur notre site communautaire Snowtricks, vous devez encore le valider en cliquant sur ce lien présent <a href="https://localhost:8000/verification/' . $token . '">ici</a>.</p><p>Merci pour votre confiance</p><p>L\'équipe Snowtrick</p>');

            $mailer->send($email);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le compte a bien été créé mais doit encore être validé : regardez vos mails !');

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verification/{token}", name="app_verify_email", methods={"GET", "POST"})
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->addFlash('success', 'Votre adresse email a bien été vérifiée.');

        return $this->redirectToRoute('app_register');
    }
}
