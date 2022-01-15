<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail($address, $submittedToken, $user)
    {
        $email = (new TemplatedEmail())
            ->from('marc.lassort@gmail.com')
            ->to($address)
            ->subject('Snowtricks - Confirmation du compte')
            ->htmlTemplate('email/welcome.html.twig')
            ->context([
                'token' => $submittedToken,
                'user' => $user
            ]);

        $this->mailer->send($email);
    }

    public function sendNewPassword($address, $submittedToken, $user)
    {
        $email = (new TemplatedEmail())
            ->from('marc.lassort@gmail.com')
            ->to($address)
            ->subject('Snowtricks - Création nouveau mot de passe')
            ->htmlTemplate('email/reset_password.html.twig')
            ->context([
                'token' => $submittedToken,
                'user' => $user
            ]);

        $this->mailer->send($email);
    }
}
