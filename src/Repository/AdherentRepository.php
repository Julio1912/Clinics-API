<?php

namespace App\Repository;

use App\Entity\Adherent;
use App\Entity\Establishment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Adherent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adherent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adherent[]    findAll()
 * @method Adherent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdherentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adherent::class);
    }

    // /**
    //  * @return Adherent[] Returns an array of Adherent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Adherent
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getNumberWorkersByEstablishment(Establishment $establishment){
        return $this->createQueryBuilder('a')
                    ->andWhere('a.establishment = :val')
                    ->andWhere('a.category = :val1')
                    ->setParameter('val', $establishment)
                    ->setParameter('val1', '000')
                    ->getQuery()->getScalarResult()
        ;
    }
    public function getWorkersByEstablishment(Establishment $establishment){
        return $this->createQueryBuilder('a')
                    ->andWhere('a.establishment = :val')
                    ->andWhere('a.category = :val1')
                    ->setParameter('val', $establishment)
                    ->setParameter('val1', '000')
        ;
    }
    public function getNumberChildMember(Adherent $adherent){
        return $this->createQueryBuilder('a')
                    ->andWhere('a.worker = :val')
                    ->andWhere('a.category = :val1')
                    ->setParameter('val', $adherent)
                    ->setParameter('val1', '002')
                    ->getQuery()->getScalarResult()
        ; 
    }
    public function getFamilies(Adherent $adherent){
        return $this->createQueryBuilder('a')
                    ->andWhere('a.worker = :val')
                    ->setParameter('val', $adherent->getWorker())
                    ->andWhere('a <> :val1')
                    ->setParameter('val1', $adherent)
                    ->getQuery()->getResult()
        ;
    }
}
