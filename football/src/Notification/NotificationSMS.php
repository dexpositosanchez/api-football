<?php

namespace App\Notification;

/**
 * Clase para el envío de notificaciones por SMS
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class NotificationSMS implements NotificationInterface
{
    public function sendNotification(string $message): bool
    {
        //TODO
        return true;
    }
}

