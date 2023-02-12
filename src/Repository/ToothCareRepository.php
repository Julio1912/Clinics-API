<?php

namespace App\Repository;

use App\Entity\ToothCare;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ToothCare|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToothCare|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToothCare[]    findAll()
 * @method ToothCare[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToothCareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToothCare::class);
    }

    // /**
    //  * @return ToothCare[] Returns an array of ToothCare objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ToothCare
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
