<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO para liberar un futbolista
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ReleaseFootballerDTO
{
    #[Assert\NotBlank(message: 'Este dato es obligatorio y no puede estar vacío')]
    private $footballer;

    public function __construct(array $data)
    {
        $this->footballer = $data['footballer'] ?? '';
    }

    public function getFootballer(): string { return $this->footballer; }
}

