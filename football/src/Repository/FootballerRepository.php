<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\Footballer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * Repositorio para recoger datos de futbolistas desde base de datos
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class FootballerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Footballer::class);
    }
    
    /**
     * Obtiene los futbolistas de un club o libres si el objeto $club es nulo
     * @param Club              $club:       objeto del club
     * @param array             $parameters: parámetros de filtro y paginado
     * @return array
     */
    public function findByClub(?Club $club, array $parameters): array
    {
        $qb = $this->createQueryBuilder('f');

        if ($club !== null) {
            $qb->where('f.club = :club')
               ->setParameter('club', $club);
        } else {
            $qb->where($qb->expr()->isNull('f.club'));
        }
        $fields = ['name', 'surname'];
        if (!empty($parameters['filter']) && !empty($parameters['value']) && in_array($parameters['filter'], $fields, true)) {
            $qb->andWhere("f.{$parameters['filter']} LIKE :value")
                ->setParameter('value', '%' . $parameters['value'] . '%');
        }

        if (!empty($parameters['limit'])) {
            $page = isset($parameters['page']) ? (int) $parameters['page'] : 1;
            $limit = (int) $parameters['limit'];
            $offset = ($page - 1) * $limit;

            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
