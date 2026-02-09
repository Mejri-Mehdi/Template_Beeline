<?php

namespace App\Repository;

use App\Entity\Banque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Banque>
 */
class BanqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Banque::class);
    }

    /**
     * Find active banks
     */
    public function findActiveBanques(): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.statut = :statut')
            ->setParameter('statut', 'active')
            ->orderBy('b.nom_bq', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find pending banks (for admin approval)
     */
    public function findPendingBanques(): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.statut = :statut')
            ->setParameter('statut', 'pending')
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get bank statistics
     */
    public function getBanqueStatistics(int $banqueId): array
    {
        $qb = $this->createQueryBuilder('b')
            ->select('COUNT(DISTINCT a.id) as total_agences')
            ->addSelect('COUNT(DISTINCT u.id) as total_clients')
            ->addSelect('COUNT(DISTINCT s.id) as total_services')
            ->addSelect('COUNT(DISTINCT o.id) as total_offres')
            ->addSelect('COUNT(DISTINCT r.id) as total_rendez_vous')
            ->leftJoin('b.agences', 'a')
            ->leftJoin('b.utilisateurs', 'u')
            ->leftJoin('b.services', 's')
            ->leftJoin('b.offres', 'o')
            ->leftJoin('b.rendezVous', 'r')
            ->where('b.id = :banqueId')
            ->setParameter('banqueId', $banqueId)
            ->getQuery()
            ->getSingleResult();

        return $qb;
    }
}
