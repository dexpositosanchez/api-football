<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Excepción que se lanza cuando se intenta liberar a un entrenador y ya esta libre
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class FreeCoachException extends HttpException
{
    public function __construct(string $message = "Este entrenador ya está libre")
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, $message);
    }

    public function toJsonResponse(): JsonResponse
    {
        return new JsonResponse([
            'status' => 'error',
            'message' => $this->getMessage()
        ], $this->getStatusCode());
    }
}


