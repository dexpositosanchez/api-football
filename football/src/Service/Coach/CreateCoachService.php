<?php

namespace App\Service\Coach;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Coach;
use App\DTO\CreatePersonDTO;

/**
 * Servicio para crear un entrenador
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class CreateCoachService
{
    private $em; 
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param CreatePersonDTO $dto: datos recibidos en la petición
     * @return Coach
     */
    public function execute(CreatePersonDTO $dto): Coach
    {
        $coach = new Coach($dto->getName(), $dto->getSurname());
        $this->em->persist($coach);
        $this->em->flush();
        return $coach;
    }
}

