<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        try {
            // SMTP vers Mailhog (conteneur Docker)
            $this->mail->isSMTP();
            $this->mail->Host = 'mailhog';       // 👈 nom du service Docker
            $this->mail->Port = 1025;            // 👈 port SMTP de Mailhog
            $this->mail->SMTPAuth = false;       // 👈 pas besoin d'authentification
            $this->mail->SMTPSecure = false;

            // Expéditeur par défaut
            $this->mail->setFrom('no-reply@arpfractal.local', 'Arpèges Fractals');

            // Format HTML
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';

        } catch (Exception $e) {
            error_log('Erreur configuration mail : ' . $e->getMessage());
        }
    }

    public function send(string $to, string $subject, string $body): bool
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->AltBody = strip_tags($body);

            return $this->mail->send();

        } catch (Exception $e) {
            error_log('Erreur envoi email : ' . $e->getMessage());
            return false;
        }
    }
}
