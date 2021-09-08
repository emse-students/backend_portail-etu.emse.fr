<?php

namespace App\Repository;

use App\Entity\EventBand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventBand|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventBand|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventBand[]    findAll()
 * @method EventBand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventBandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventBand::class);
    }

    // /**
    //  * @return EventBand[] Returns an array of EventBand objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventBand
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
