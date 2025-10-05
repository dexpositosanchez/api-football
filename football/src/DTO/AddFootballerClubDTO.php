<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO para añadir un futbolista a un club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class AddFootballerClubDTO extends AddPersonClubDTO
{
    #[Assert\NotBlank(message: 'Este dato es obligatorio y no puede estar vacío')]
    private $footballer;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->footballer = $data['footballer'] ?? '';
    }

    public function getFootballer(): int { return (int)$this->footballer; }
}

