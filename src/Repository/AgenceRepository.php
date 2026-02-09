<?php

namespace App\Repository;

use App\Entity\Agence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Agence>
 */
class AgenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agence::class);
    }

    /**
     * Find agencies by bank
     */
    public function findByBanque(int $banqueId): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.banque = :banque')
            ->setParameter('banque', $banqueId)
            ->orderBy('a.nom_ag', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
