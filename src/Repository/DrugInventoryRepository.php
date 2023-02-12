<?php

namespace App\Repository;

use App\Entity\DrugInventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DrugInventory|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrugInventory|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrugInventory[]    findAll()
 * @method DrugInventory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrugInventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrugInventory::class);
    }

    // /**
    //  * @return DrugInventory[] Returns an array of DrugInventory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DrugInventory
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findPerDrug($drug , $years  , $month)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.drug = :drug')
            ->andWhere('Month(d.created) = :month')
            ->andWhere('YEAR(d.created) = :years')
            ->andWhere('d.type = :type OR d.type= :type1')
            ->setParameter('drug', $drug)
            ->setParameter('month', $month)
            ->setParameter('years', $years)
            ->setParameter('type', 'NEW_DRUG')
            ->setParameter('type1', 'ADD_DRUG_QUANTITIES')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOutPerDrug($drug , $years  , $month)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.drug = :drug')
            ->andWhere('Month(d.created) = :month')
            ->andWhere('YEAR(d.created) = :years')
            ->andWhere('d.type = :type')
            // ->andWhere('d.type != :type OR d.type != :type1')
            ->setParameter('drug', $drug)
            ->setParameter('month', $month)
            ->setParameter('years', $years)
            ->setParameter('type', 'PRESCRIPTION')
            ->getQuery()
            ->getResult()
        ;
    }
    
}
