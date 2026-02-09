<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offre>
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    /**
     * Find active offers (currently valid)
     */
    public function findActiveOffres(): array
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('o')
            ->where('o.active = :active')
            ->andWhere('(o.date_debut IS NULL OR o.date_debut <= :now)')
            ->andWhere('(o.date_fin IS NULL OR o.date_fin >= :now)')
            ->setParameter('active', true)
            ->setParameter('now', $now)
            ->orderBy('o.date_debut', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find active offers by bank
     */
    public function findActiveByBanque(int $banqueId): array
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('o')
            ->where('o.banque = :banque')
            ->andWhere('o.active = :active')
            ->andWhere('(o.date_debut IS NULL OR o.date_debut <= :now)')
            ->andWhere('(o.date_fin IS NULL OR o.date_fin >= :now)')
            ->setParameter('banque', $banqueId)
            ->setParameter('active', true)
            ->setParameter('now', $now)
            ->orderBy('o.date_debut', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all offers by bank
     */
    public function findByBanque(int $banqueId): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.banque = :banque')
            ->setParameter('banque', $banqueId)
            ->orderBy('o.date_debut', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
