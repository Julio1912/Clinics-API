<?php

namespace App\Repository;

use App\Entity\WorkIncidentLesion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkIncidentLesion|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkIncidentLesion|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkIncidentLesion[]    findAll()
 * @method WorkIncidentLesion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkIncidentLesionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkIncidentLesion::class);
    }

    // /**
    //  * @return WorkIncidentLesion[] Returns an array of WorkIncidentLesion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkIncidentLesion
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getQueryLesionEnable()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :val')
            ->setParameter('val', true)
            
        ;
    }
}
