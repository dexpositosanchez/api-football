<?php

namespace App\Notification;

/**
 * Clase para el envío de notificaciones por Whatsapp
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class NotificationWhatsapp implements NotificationInterface
{
    public function sendNotification(string $message): bool
    {
        //TODO
        return true;
    }
}

