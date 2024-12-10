<?php

namespace App\Repository;

use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<History>
 */
class HistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    // Récupérer le nombre de prêts en cours
    public function countOngoing(): int
    {
        return (int) $this->createQueryBuilder('history')
            ->select('COUNT(history.id)')
            ->where('history.is_back = 0')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Récupérer le nombre de prêts terminés
    public function countBack(): int
    {
        return (int) $this->createQueryBuilder('history')
            ->select('COUNT(history.id)')
            ->where('history.is_back = 1')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Récupérer le nombre de prêts en retard
    public function countLate(): int
    {
        return (int) $this->createQueryBuilder('history')
            ->select('COUNT(history.id)')
            ->where('history.end_date < :currentDate')
            ->andWhere('history.is_back = 0')
            ->setParameter('currentDate', new \DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return History[] Returns an array of History objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?History
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
