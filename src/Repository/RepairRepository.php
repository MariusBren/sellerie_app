<?php

namespace App\Repository;

use DateTime;
use App\Entity\Repair;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Repair>
 */
class RepairRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repair::class);
    }

    // Récupérer le nombre de réparations terminées
    public function countDone(): int
    {
        return (int) $this->createQueryBuilder('repair')
            ->select('COUNT(repair.id)')
            ->where('repair.is_done = 1')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Récupérer le nombre de réparations non terminées
    public function countToDo(): int
    {
        return (int) $this->createQueryBuilder('repair')
            ->select('COUNT(repair.id)')
            ->where('repair.is_done = 0')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Récupérer le coût total des réparations prévues/non terminées
    public function sumCostToDo(): int
    {
        return (float) $this->createQueryBuilder('repair')
            ->select('SUM(repair.cost)')
            ->where('repair.is_done = 0')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Récupérer le nombre de réparations en retard
    public function countLate(): int
    {
        return (int) $this->createQueryBuilder('repair')
            ->select('COUNT(repair.id)')
            ->where('repair.date < :currentDate')
            ->andWhere('repair.is_done = 0')
            ->setParameter('currentDate', new DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Repair[] Returns an array of Repair objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Repair
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
