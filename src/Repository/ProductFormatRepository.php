<?php

namespace App\Repository;

use App\Entity\ProductFormat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductFormat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductFormat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductFormat[]    findAll()
 * @method ProductFormat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductFormatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductFormat::class);
    }

    // /**
    //  * @return ProductFormat[] Returns an array of ProductFormat objects
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
    public function findOneBySomeField($value): ?ProductFormat
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
