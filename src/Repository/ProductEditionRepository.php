<?php

namespace App\Repository;

use App\Entity\ProductEdition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductEdition|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductEdition|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductEdition[]    findAll()
 * @method ProductEdition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductEditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductEdition::class);
    }

    // /**
    //  * @return ProductEdition[] Returns an array of ProductEdition objects
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
    public function findOneBySomeField($value): ?ProductEdition
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
