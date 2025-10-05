<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO para añadir una persona a un club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
abstract class AddPersonClubDTO
{
    #[Assert\NotBlank(message: 'Este dato es obligatorio y no puede estar vacío')]
    private $club;

    #[Assert\GreaterThanOrEqual(value: 0, message: 'Este dato no puede ser negativo')]
    #[Assert\Type(type: 'numeric', message: 'Este dato debe ser un número')]
    #[Assert\NotBlank(message: 'Este dato es obligatorio y no puede estar vacío')]
    private $salary;

    public function __construct(array $data)
    {
        $this->club = $data['club'] ?? '';
        $this->salary = isset($data['salary']) ? $this->formatFloat($data['salary']) : '';
    }
    
    public function getClub(): int { return (int)$this->club; }
    public function getSalary(): float { return (float)$this->salary; }

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

