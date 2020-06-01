<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatisticsController extends AbstractController
{
    /**
     * @Route("/statistics", name="statistics")
     */
    public function index(EntityManagerInterface $em, Security $security) {
        $params = [];
        $repo = $em->getRepository(Product::class);
        $userLogged = $security->getUser();
        $vQuery = $repo->createQueryBuilder("product");
        $vQuery->innerJoin("App\Entity\User", "user", Join::WITH, "product.publisher = user.id");
        $vQuery->innerJoin("App\Entity\Sale", "sale", Join::WITH, "product.id = sale.product");
        $vQuery->addSelect("COUNT(sale) as amout");
        $vQuery->addSelect("SUM(sale.price) as total");
        $vQuery->groupBy("sale.product");
        $vQuery->orderBy("amout", "ASC");
        $vQuery->andWhere("user.id = :userID");
        $vQuery->setMaxResults(6);
        $vQuery->setParameter('userID', $userLogged->getId() ?? 0);

        $params["ventas"] = $vQuery->getQuery()->getResult();

        dump($vQuery->getQuery()->getResult());

        return $this->render('statistics/index.html.twig', $params);
    }
}
