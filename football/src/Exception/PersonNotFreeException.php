<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Excepción que se lanza cuando se intenta añadir una persona a un club
 * y no está libre
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class PersonNotFreeException extends HttpException
{
    public function __construct(string $message = "Esta persona no está libre")
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


