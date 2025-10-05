<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Excepción que se lanza cuando no encuentra el dato en el sistema
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class NotFoundException extends HttpException
{
    public function __construct(string $message = "Dato no encontrado")
    {
        parent::__construct(Response::HTTP_NOT_FOUND, $message);
    }

    public function toJsonResponse(): JsonResponse
    {
        return new JsonResponse([
            'status' => 'error',
            'message' => $this->getMessage()
        ], $this->getStatusCode());
    }
}


