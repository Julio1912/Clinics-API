<?php

namespace App\Repository;

use App\Entity\Consultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Consultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consultation[]    findAll()
 * @method Consultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consultation::class);
    }

    // /**
    //  * @return Consultation[] Returns an array of Consultation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Consultation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findConsultation($society , $month)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.adherent' , 'ca')
            ->innerJoin('ca.establishment' , 'cae')
            ->where('cae = :society')
            ->andWhere('Month(c.created) = :month')
            ->setParameter('society', $society)
            ->setParameter('month', $month)
            ->getQuery()
            ->getResult ()
        ;
    }

    public function findAllConsultation($society)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.adherent' , 'ca')
            ->innerJoin('ca.establishment' , 'cae')
            ->where('cae = :society')
            ->setParameter('society', $society)
            ->getQuery()
            ->getResult ()
        ;
    }

    public function findConsultationPerDiagnostic($diagnostic ,$year , $month)
    {
        return $this->createQueryBuilder('c')
            ->where('c.diagnostic = :diagnostic')
            ->andWhere('MONTH(c.created) = :month')
            ->andWhere('YEAR(c.created) = :year')
            ->setParameter('diagnostic', $diagnostic)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult ()
        ;
    }

    public function countConsultationPerDiagnosticYears($diagnostic ,$year )
    {
        return $this->createQueryBuilder('c')
            ->where('c.diagnostic = :diagnostic')
            ->andWhere('YEAR(c.created) = :year')
            ->setParameter('diagnostic', $diagnostic)
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult ()
        ;
    }

    public function findConsultationPerAgeMale($diagnostic,$year , $month)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.adherent' ,'ca')
            ->where('c.diagnostic = :diagnostic')
            ->andWhere('YEAR(c.created) = :year')
            ->andWhere('MONTH(c.created) = :month')
            ->andWhere('ca.gender = :gender OR c.gender = :gender')
            ->setParameter('diagnostic', $diagnostic)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('gender', 'homme')
            ->getQuery()
            ->getResult ()
        ;
    }

    public function findConsultationPerAgeFemale($diagnostic,$year , $month)
    {
       
        return $this->createQueryBuilder('c')
            ->leftJoin('c.adherent' ,'ca')
            ->where('c.diagnostic = :diagnostic')
            ->andWhere('YEAR(c.created) = :year')
            ->andWhere('MONTH(c.created) = :month')
            ->andWhere('ca.gender = :gender OR c.gender = :gender')
            ->setParameter('diagnostic', $diagnostic)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('gender', 'femme')
            ->getQuery() 
            ->getResult () ;
        ;
    }

    public function findTime($society)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.adherent' , 'ca')
            ->innerJoin('ca.establishment' , 'cae')
            ->where('cae = :society')
            ->setParameter('society', $society)
            ->select('MONTH(c.created) as month , YEAR(c.created) as year')
            ->groupBy('month , year')
            ->orderBy('month , year' , 'DESC')
            // ->groupBy('year')
            ->getQuery()
            ->getResult ()
        ;
    }

    public function findConsultationPerToothCare($toothcare ,$year , $month)
    {
        return $this->createQueryBuilder('c')
            ->where('c.toothcare = :toothcare')
            ->andWhere('MONTH(c.created) = :month')
            ->andWhere('YEAR(c.created) = :year')
            ->setParameter('toothcare', $toothcare)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult ()
        ;
    }

    public function findConsultationToothCarePerAge($toothcare,$year , $month)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.adherent' ,'ca')
            ->where('c.toothcare = :toothcare')
            ->andWhere('YEAR(c.created) = :year')
            ->andWhere('MONTH(c.created) = :month')
            ->setParameter('toothcare', $toothcare)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult ()
        ;
    }

    public function countConsultationPerToothCareYears($toothcare ,$year )
    {
        return $this->createQueryBuilder('c')
            ->where('c.toothcare = :toothcare')
            ->andWhere('YEAR(c.created) = :year')
            ->setParameter('toothcare', $toothcare)
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult ()
        ;
    }

}
