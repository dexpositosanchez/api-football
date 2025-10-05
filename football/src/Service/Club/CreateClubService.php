<?php

namespace App\Service\Club;

use App\Entity\Club;
use App\DTO\CreateClubDTO;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Servicio para la creación de Club en el sistema
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class CreateClubService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param CreateClubDTO $dto: datos de la petición
     * @return JsonResponse
     */
    public function execute(CreateClubDTO $dto): Club
    {
        $club = new Club($dto->getName(),$dto->getBudget());
        $this->em->persist($club);
        $this->em->flush();
        return $club;
    }
}

