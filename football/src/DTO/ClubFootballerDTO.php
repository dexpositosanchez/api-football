<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO para buscar los fubtolistas de un club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ClubFootballerDTO
{
    #[Assert\NotNull(message: 'No se ha recibido el club')]
    private $id;

    public function __construct(?int $id)
    {
        $this->id = $id;
    }

    public function getId(): ?int { return $this->id; }
}

