<?php

namespace App\Service\Footballer;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClubRepository;
use App\Repository\FootballerRepository;
use App\DTO\ClubFootballerDTO;
use App\Exception\NotFoundException;

/**
 * Servicio para obtener todo los futbolistas de un club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ClubFootballerService
{
    private $footballerRepository;
    private $clubRepository;

    public function __construct(FootballerRepository $footballerRepository, ClubRepository $clubRepository)
    {
        $this->footballerRepository = $footballerRepository;
        $this->clubRepository = $clubRepository;
    }

    /**
     * @param ClubFootballerDTO $dto:        datos recibidos en la petición
     * @param array             $parameters: parámetros de filtro y paginado
     * @return array
     */
    public function execute(ClubFootballerDTO $dto, array $parameters): array
    {
        $club = $this->clubRepository->find($dto->getId());
        if (!$club) {
            throw new NotFoundException("Club no encontrado");
        }
        return [$club, $this->footballerRepository->findByClub($club, $parameters)];
    }
}

