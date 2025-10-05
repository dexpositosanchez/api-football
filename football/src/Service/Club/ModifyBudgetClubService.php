<?php

namespace App\Service\Club;

use App\Entity\Club;
use App\Repository\ClubRepository;
use App\Repository\PersonRepository;
use App\DTO\ModifyBudgetClubDTO;
use App\Exception\NotFoundException;
use App\Exception\ModifyBudgetException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Servicio para modificar el presupuesto de un Club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ModifyBudgetClubService
{
    private $em;
    private $personRepository;
    private $clubRepository;

    public function __construct(EntityManagerInterface $em, PersonRepository $personRepository, ClubRepository $clubRepository)
    {
        $this->em = $em;
        $this->personRepository = $personRepository;
        $this->clubRepository = $clubRepository;
    }

    /**
     * @param ModifyBudgetClubDTO $dto: datos de la petición
     * @return Club
     */
    public function execute(ModifyBudgetClubDTO $dto): Club
    {
        $club = $this->clubRepository->find($dto->getClub());
        if (!$club) {
            throw new NotFoundException("Club no encontrado");
        }
        $salaries = $this->personRepository->getTotalSalariesByClub($club);
        if ($salaries > $dto->getBudget()) {
            throw new ModifyBudgetException("Modificación rechazada, los salarios actuales es mayor que el nuevo presupuesto: ".$salaries." > ".$dto->getBudget());
        }
        $club->setBudget($dto->getBudget());
        $this->em->persist($club);
        $this->em->flush();
        return $club;
    }
}

