<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController {
    /**
     * @Route("/account", name="account")
     */
    public function index(Security $security, EntityManagerInterface $em) {
        $user = $security->getUser();
        $template = "";
        $params = [];
        $query = null;

        if (in_array("ROLE_BUYER", $user->getRoles())) {
            $template = "account/index.html.twig";
        } else if (in_array("ROLE_SELLER", $user->getRoles())) {
            $template = "account/seller.html.twig";
            $repo = $em->getRepository(Product::class);
            $query = $repo->createQueryBuilder("product");
            $query->innerJoin("App\Entity\User", "user", Join::WITH, "product.publisher = user.id");
            $query->innerJoin("App\Entity\Sale", "sale", Join::WITH, "product.id = sale.product");
            $query->addSelect("COUNT(sale) as amout");
            $query->addSelect("SUM(sale.price) as total");
            $query->groupBy("sale.product");
            $query->orderBy("amout", "ASC");
            $query->setMaxResults(6);

            dump($query->getQuery()->getResult());
            $params["products"] = $query->getQuery()->getResult();
        }

        return $this->render($template, $params);
    }

    /**
     * @Route("/account/id/{id}", name="account_id")
     */
    public function idView(int $id = 0, Security $security, EntityManagerInterface $em, Request $request, PaginatorInterface $paginator) {
        $user = $em->getRepository(User::class)->findOneBy(["id" => $id]);
        $page = $request->query->get('page', 1);
        $private = false;

        $repo = $em->getRepository(Product::class);
        $query = $repo->createQueryBuilder("product");
        $query->andWhere("product.publisher = :val");
        $query->setParameter('val', $user->getId());

        $pagination = $paginator->paginate($query, $page, 16);

        if (in_array("ROLE_SELLER", $user->getRoles())) {
            $private = true;
        }

        return $this->render("account/details.html.twig", [
            "user" => $private? $user:null,
            "pagination" => $pagination,
        ]);
    }
}
