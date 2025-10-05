<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO para modificar el presupuesto del club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ModifyBudgetClubDTO
{
    #[Assert\NotBlank(message: 'Este dato es obligatorio y no puede estar vacío')]
    private $club;

    #[Assert\GreaterThanOrEqual(value: 0, message: 'Este dato no puede ser negativo')]
    #[Assert\Type(type: 'numeric', message: 'Este dato debe ser un número')]
    #[Assert\NotBlank(message: 'Este dato es obligatorio y no puede estar vacío')]
    private $budget;

    public function __construct(array $data)
    {
        $this->club = $data['club'] ?? '';
        $this->budget = isset($data['budget']) ? $this->formatFloat($data['budget']) : '';
    }

    public function getClub(): int { return (int)$this->club; }
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

