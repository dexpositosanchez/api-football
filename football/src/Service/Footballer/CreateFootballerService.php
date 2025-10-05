<?php

namespace App\Service\Footballer;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Footballer;
use App\DTO\CreatePersonDTO;

/**
 * Servicio para crear un futbolista
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class CreateFootballerService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param CreatePersonDTO $dto: datos recibidos en la petición
     * @return Footballer
     */
    public function execute(CreatePersonDTO $dto): Footballer
    {
        $footballer = new Footballer($dto->getName(), $dto->getSurname());
        $this->em->persist($footballer);
        $this->em->flush();
        return $footballer;
    }
}

