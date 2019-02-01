<?php

namespace App\Repository;

use App\Entity\PaymentMeans;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaymentMeans|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentMeans|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentMeans[]    findAll()
 * @method PaymentMeans[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentMeansRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaymentMeans::class);
    }

    // /**
    //  * @return PaymentMeans[] Returns an array of PaymentMeans objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaymentMeans
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
