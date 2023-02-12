<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByroleadmin()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"'.'ROLE_ADMIN'.'"%')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByRoleMedc(){
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"'.'ROLE_MEDC'.'"%')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByroleDrugstore()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"'.'ROLE_DRUG_STORE'.'"%')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByroleHommeAccount()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"'.'ROLE_HOME_ACCOUNT'.'"%')
            ->getQuery()
            ->getResult()
            ;
    }

    

}
