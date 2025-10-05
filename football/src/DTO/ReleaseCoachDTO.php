<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO para liberar un entrenador
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ReleaseCoachDTO
{
    #[Assert\NotBlank(message: 'Este dato es obligatorio y no puede estar vacío')]
    private $coach;

    public function __construct(array $data)
    {
        $this->coach = $data['coach'] ?? '';
    }

    public function getCoach(): string { return $this->coach; }
}

