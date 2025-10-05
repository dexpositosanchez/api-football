<?php

namespace App\Notification;

/**
 * Interfaz para los diferentes sistemas de notificación
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
interface NotificationInterface
{
    public function sendNotification(string $message): bool;
}


