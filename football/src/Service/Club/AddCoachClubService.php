<?php

namespace App\Service\Club;

use App\Repository\CoachRepository;
use App\DTO\AddCoachClubDTO;
use App\Exception\NotFoundException;
use App\Service\Club\AddPersonClubService;

/**
 * Servicio para añadir un Coach a un Club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class AddCoachClubService
{
    private $coachRepository;
    private $service;

    public function __construct(CoachRepository $coachRepository, AddPersonClubService $service)
    {
        $this->coachRepository = $coachRepository;
        $this->service = $service;
    }

    /**
     * Añade un entrenador a un club
     * @param AddCoachClubDTO $dto: datos de la petición
     * @return array
     */
    public function execute(AddCoachClubDTO $dto): array
    {
        $coach = $this->coachRepository->find($dto->getCoach());
        if (!$coach) {
            throw new NotFoundException("Entrenador no encontrado");
        }
        return $this->service->execute($coach, $dto);
    }
}

