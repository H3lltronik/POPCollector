<?php

namespace App\Repository;

use App\Entity\Verifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Verifications|null find($id, $lockMode = null, $lockVersion = null)
 * @method Verifications|null findOneBy(array $criteria, array $orderBy = null)
 * @method Verifications[]    findAll()
 * @method Verifications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerificationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Verifications::class);
    }

    // /**
    //  * @return Verifications[] Returns an array of Verifications objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Verifications
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
