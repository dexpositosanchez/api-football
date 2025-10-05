<?php

namespace App\Repository;

use App\Entity\Person;
use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repositorio para recoger datos de personas desde base de datos
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * Obtiene el total de los salarios de las personas asignadas a un club
     * @param Club $club: objeto del club
     * @return float
     */
    public function getTotalSalariesByClub(Club $club): float
    {
        return (float)$this->createQueryBuilder('p')
                ->select('COALESCE(SUM(p.salary), 0)')
                ->where('p.club = :club')
                ->setParameter('club', $club)
                ->getQuery()
                ->getSingleScalarResult();
    }
}
