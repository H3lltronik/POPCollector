<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Subscription;
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

            $params["products"] = $query->getQuery()->getResult();
        }

        return $this->render($template, $params);
    }

    /**
     * @Route("/account/id/{id}", name="account_id")
     */
    public function idView(int $id = 0, Security $security, EntityManagerInterface $em, Request $request, PaginatorInterface $paginator) {
        $user = $em->getRepository(User::class)->findOneBy(["id" => $id]);
        $userLogged = $security->getUser();
        $page = $request->query->get('page', 1);
        $private = false;
        $subscribed = false;

        $repo = $em->getRepository(Product::class);
        $query = $repo->createQueryBuilder("product");
        $query->andWhere("product.publisher = :val");
        $query->setParameter('val', $user->getId());

        $pagination = $paginator->paginate($query, $page, 16);

        if (isset($user))
        if (in_array("ROLE_SELLER", $user->getRoles())) {
            $private = true;
        }

        $roles = ($userLogged)? $userLogged->getRoles():[];
        $subscriptions = ($userLogged)? $userLogged->getSubscriptions():[];

        foreach ($subscriptions as $subscription) {
            if ($subscription->getId() == $user->getId())
                $subscribed = true;
        }

        return $this->render("account/details.html.twig", [
            "user" => $private? $user:null,
            "pagination" => $pagination,
            "isBuyer" => in_array("ROLE_BUYER", $roles),
            "subscribed" => $subscribed
        ]);
    }

    /**
     * @Route("/subscribe/id/", name="subscribe_id")
     */
    public function subscribe(Security $security, EntityManagerInterface $em, Request $request) {
        $userLogged = $security->getUser();
        $account = $request->query->get('account', 0);

        $user = $em->getRepository(User::class)->findOneBy(["id" => $account]);
        $userLogged->addSubscription($user);

        $em->persist($userLogged);
        $em->flush();

        return $this->json(["status" => "ok"], 200);
    }
}
