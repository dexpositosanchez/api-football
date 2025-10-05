<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO para crear un club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class CreateClubDTO
{
    #[Assert\NotBlank(message: 'Este dato es obligatorio y no puede estar vacío')]
    private $name;

    #[Assert\GreaterThanOrEqual(value: 0, message: 'Este dato no puede ser negativo')]
    #[Assert\Type(type: 'numeric', message: 'Este dato debe ser un número')]
    #[Assert\NotBlank(message: 'Este dato es obligatorio y no puede estar vacío')]
    private $budget;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->budget = isset($data['budget']) ? $this->formatFloat($data['budget']) : '';
    }

    public function getName(): string { return $this->name; }
    public function getBudget(): float { return (float)$this->budget; }

    /**
     * Cambia las comas por puntos en un float
     * @param string $value: presupuesto recibido
     * @return float;
     */
    private function formatFloat(string $value): string
    {
        return str_replace(",",".",$value);
    }
}

