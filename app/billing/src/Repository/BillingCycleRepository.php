<?php

namespace App\Repository;

use App\Entity\BillingCycle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BillingCycle>
 *
 * @method BillingCycle|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillingCycle|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillingCycle[]    findAll()
 * @method BillingCycle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillingCycleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillingCycle::class);
    }

//    /**
//     * @return BillingCycle[] Returns an array of BillingCycle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BillingCycle
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
