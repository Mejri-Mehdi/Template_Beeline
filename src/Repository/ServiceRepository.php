<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * Find available services by bank
     */
    public function findAvailableByBanque(int $banqueId): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.banque = :banque')
            ->andWhere('s.disponible = :disponible')
            ->setParameter('banque', $banqueId)
            ->setParameter('disponible', true)
            ->orderBy('s.nom_service', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all services by bank (including unavailable)
     */
    public function findByBanque(int $banqueId): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.banque = :banque')
            ->setParameter('banque', $banqueId)
            ->orderBy('s.nom_service', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
