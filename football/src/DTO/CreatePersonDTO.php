<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO para crear una persona
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class CreatePersonDTO
{
    #[Assert\NotBlank(message: 'Este dato es obligatorio y no puede estar vacío')]
    private $name;

    #[Assert\NotBlank(message: 'Este dato es obligatorio y no puede estar vacío')]
    private $surname;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->surname = $data['surname'] ?? '';
    }

    public function getName(): string { return $this->name; }
    public function getSurname(): string { return $this->surname; }
}

