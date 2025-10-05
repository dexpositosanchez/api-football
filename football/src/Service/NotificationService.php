<?php

namespace App\Service;

/**
 * Servicio para gestionar las notificaciones
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class NotificationService
{
    private $methods;

    public function __construct(array $methods)
    {
        $this->methods = $methods;
    }

    /**
     * Envía la notificación por todos los métodos disponibles
     * @param string $message: mensaje de la notificación
     * @return bool
     */
    public function sendNotification(string $message): bool
    {
        $sent = true;
        foreach ($this->methods as $method) {
            $sent = $sent && $method->sendNotification($message);
        }
        return $sent;
    }
}
