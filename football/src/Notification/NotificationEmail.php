<?php

namespace App\Notification;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Throwable;

/**
 * Clase para el envío de notificaciones por email
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class NotificationEmail implements NotificationInterface
{
    private string $to;
    private MailerInterface $mailer;

    public function __construct(string $to, MailerInterface $mailer)
    {
        $this->to = $to;
        $this->mailer = $mailer;
    }

    /**
     * Envía la notificacion por email
     * @param string $message: mensaje de la notificación
     * @return bool
     */
    public function sendNotification(string $message): bool
    {
        try {
            $email = (new Email())
            ->from("noreply@football.com")
            ->to($this->to)
            ->subject('Notificación del sistema')
            ->text($message);
            $this->mailer->send($email);
            return true;
        } catch (Throwable $th) {
            return false;
        }
    }
}

