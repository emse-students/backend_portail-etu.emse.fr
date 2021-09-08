<?php

namespace App\Repository;

use App\Entity\FormInput;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormInput|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormInput|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormInput[]    findAll()
 * @method FormInput[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormInputRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormInput::class);
    }

    // /**
    //  * @return FormInput[] Returns an array of FormInput objects
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
    public function findOneBySomeField($value): ?FormInput
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
