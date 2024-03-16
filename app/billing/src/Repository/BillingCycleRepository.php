<?php

namespace App\Repository;

use App\Entity\BillingCycle;
use App\Entity\User;
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

    public function add(BillingCycle $cycle): BillingCycle
    {
        return $this->save($cycle);
    }

    public function findActiveForOwner(User $owner): ?BillingCycle
    {
        return $this->findOneBy([
            'owner' => $owner->getId(),
            'status' => BillingCycle::ACTIVE
        ], ['createdAt' => 'DESC']);
    }

    private function save(BillingCycle $cycle): BillingCycle
    {
        $this->getEntityManager()->persist($cycle);
        $this->getEntityManager()->flush();

        return $cycle;
    }
}
