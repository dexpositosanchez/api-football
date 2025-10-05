<?php

namespace App\Service\Footballer;

use App\Repository\FootballerRepository;

/**
 * Servicio para obtener todo los futbolistas libres del sistema
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class FreeFootballerService
{
    private $footballerRepository;

    public function __construct(FootballerRepository $footballerRepository)
    {
        $this->footballerRepository = $footballerRepository;
    }

    /**
     * @param array $parameters: parámetros de filtro y paginado
     * @return array
     */
    public function execute(array $parameters): array
    {
        return $this->footballerRepository->findByClub(null, $parameters);
    }
}

