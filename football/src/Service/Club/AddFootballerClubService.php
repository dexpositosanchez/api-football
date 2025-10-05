<?php

namespace App\Service\Club;

use App\DTO\AddFootballerClubDTO;
use App\Exception\NotFoundException;
use App\Repository\FootballerRepository;
use App\Service\Club\AddPersonClubService;

/**
 * Servicio para añadir un Footballer a un Club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class AddFootballerClubService
{
    private $footballerRepository;
    private $service;

    public function __construct(FootballerRepository $footballerRepository, AddPersonClubService $service)
    {
        $this->footballerRepository = $footballerRepository;
        $this->service = $service;
    }

    /**
     * @param AddFootballerClubDTO $dto: datos de la petición
     * @return array
     */
    public function execute(AddFootballerClubDTO $dto): array
    {
        $footballer = $this->footballerRepository->find($dto->getFootballer());
        if (!$footballer) {
            throw new NotFoundException("Futbolista no encontrado");
        }
        return $this->service->execute($footballer, $dto);
    }
}

