<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repositorio para recoger datos de clubes desde base de datos
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Club::class);
    }
}
