<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Excepción que se lanza cuando se intenta añadir una persona a un club
 *  y los salarios es mayor al nuevo presupuesto
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class AddPersonException extends HttpException
{
    public function __construct(string $message = "")
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


