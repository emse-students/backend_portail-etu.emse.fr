<?php

namespace App\Repository;

use App\Entity\FormOutput;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FormOutput|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormOutput|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormOutput[]    findAll()
 * @method FormOutput[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormOutputRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FormOutput::class);
    }

    // /**
    //  * @return FormOutput[] Returns an array of FormOutput objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FormOutput
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
