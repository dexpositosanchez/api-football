<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Servicio para formatear los errores de las validaciones
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ValidationErrorsService
{
    public function format(ConstraintViolationListInterface $errors): array
    {
        $list = [];
        foreach ($errors as $error) {
            $list[$error->getPropertyPath()] = $error->getMessage();
        }

        return [
            'status' => 'error',
            'errors' => $list
        ];
    }
}