<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO para añadir un entrenador a un club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class AddCoachClubDTO extends AddPersonClubDTO
{
    #[Assert\NotBlank(message: 'Este dato es obligatorio y no puede estar vacío')]
    private $coach;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->coach = $data['coach'] ?? '';
    }

    public function getCoach(): int { return (int)$this->coach; }
}

