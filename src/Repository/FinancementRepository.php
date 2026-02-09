<?php

namespace App\Repository;

use App\Entity\Financement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Financement>
 */
class FinancementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Financement::class);
    }

    /**
     * Find financing requests by client
     */
    public function findByClient(int $clientId): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.client = :client')
            ->setParameter('client', $clientId)
            ->orderBy('f.date_demande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find financing requests by bank
     */
    public function findByBanque(int $banqueId): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.banque = :banque')
            ->setParameter('banque', $banqueId)
            ->orderBy('f.date_demande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find pending financing requests by bank
     */
    public function findPendingByBanque(int $banqueId): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.banque = :banque')
            ->andWhere('f.statut = :statut')
            ->setParameter('banque', $banqueId)
            ->setParameter('statut', 'pending')
            ->orderBy('f.date_demande', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Count financing requests by status and bank
     */
    public function countByStatutAndBanque(string $statut, int $banqueId): int
    {
        return $this->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->where('f.banque = :banque')
            ->andWhere('f.statut = :statut')
            ->setParameter('banque', $banqueId)
            ->setParameter('statut', $statut)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get total financement amount by bank
     */
    public function getTotalMontantByBanque(int $banqueId): float
    {
        $result = $this->createQueryBuilder('f')
            ->select('SUM(f.montant_demande)')
            ->where('f.banque = :banque')
            ->andWhere('f.statut = :statut')
            ->setParameter('banque', $banqueId)
            ->setParameter('statut', 'approved')
            ->getQuery()
            ->getSingleScalarResult();

        return $result ?? 0.0;
    }
}
