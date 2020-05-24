<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function addSelectFields($query, $alias, $fields) {
        $fieldsQuery = " ";

        foreach ($fields as $field) {
            $fieldsQuery .= "$alias.$field,";
        }
        $fieldsQuery = rtrim($fieldsQuery, ",");

        $query->addSelect($fieldsQuery);

        return $query;
    }

    public function addfilterByLike($query, string $alias, array $fields, string $searchTerm) {
        if ($searchTerm) {
            $searchQuery = [];
            foreach ($fields as $field) {
                $searchQuery[] = "$alias.$field LIKE :searchTerm";
            }
            $query = $query->andWhere(join(" or ", $searchQuery))->setParameter('searchTerm', "%$searchTerm%");
        }

        return $query;
    }

    public function addJoinTo($query, string $joinClass, $alias, string $mappedAttr) {
        $query->innerJoin($joinClass, $alias, Join::WITH, "$alias = $mappedAttr");

        return $query;
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
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
