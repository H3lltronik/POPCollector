<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Subscription;
use App\Services\UserService;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController {
    /**
     * @Route("/account", name="account")
     */
    public function index(Security $security, EntityManagerInterface $em, UserService $userService, PaginatorInterface $paginator, Request $request) {
        $user = $security->getUser();
        $template = "";
        $params = [];
        $query = null;
        $page = $request->query->get('page', 1);

        if ($userService->hasRole(["ROLE_BUYER"]) || $userService->hasRole(["ROLE_VERIFICATOR"])) {
            $template = "account/index.html.twig";
        } else if ($userService->hasRole(["ROLE_SELLER"])) {
            $template = "account/seller.html.twig";
            $repo = $em->getRepository(Product::class);
            $query = $repo->createQueryBuilder("product");
            $query->andWhere("product.publisher = :val");
            $query->setParameter('val', $user->getId());
            
            $pagination = $paginator->paginate($query, $page, 16);
            
            $params["pagination"] = $pagination;
            $params["products"] = $query->getQuery()->getResult();
        } else if ($userService->hasRole(["ROLE_ADMIN"])) {
            $template = "account/admin.html.twig";
            $repo = $em->getRepository(Product::class);
            $query = $repo->createQueryBuilder("product");
            $query->andWhere("product.publisher = :val");
            $query->setParameter('val', $user->getId());

            $pagination = $paginator->paginate($query, $page, 16);

            $params["products"] = $query->getQuery()->getResult();
            $params["pagination"] = $pagination;
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
        $private = true;
        $suspended = false;
        $subscribed = false;

        $repo = $em->getRepository(Product::class);
        $query = $repo->createQueryBuilder("product");
        $query->andWhere("product.publisher = :val");
        $query->setParameter('val', $user->getId());

        $pagination = $paginator->paginate($query, $page, 16);

        if (isset($user))
        if (in_array("ROLE_SELLER", $user->getRoles()) || in_array("ROLE_ADMIN", $userLogged->getRoles())) {
            $private = false;
        }

        if (!$user->getIsActive()) {
            $suspended = true;
        }

        $roles = ($userLogged)? $userLogged->getRoles():[];
        $subscriptions = ($userLogged)? $userLogged->getSubscriptions():[];

        foreach ($subscriptions as $subscription) {
            if ($subscription->getId() == $user->getId())
                $subscribed = true;
        }

        return $this->render("account/details.html.twig", [
            "user" => (!$private)? $user:null,
            "suspended" => $suspended,
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

    /**
     * @Route("/account-delete/id/", name="account_delete")
     */
    public function deleteAccount(Security $security, EntityManagerInterface $em, Request $request) {
        $account = $request->query->get('account', 0);
        $message = "El usuario ha sido deshabilitado";

        $user = $em->getRepository(User::class)->findOneBy(["id" => $account]);
        if (!$user->getIsActive()) {
            $user->setIsActive(true);
            $message = "El usuario ha sido habilitado";
        }
        else
            $user->setIsActive(false);

        $em->persist($user);
        $em->flush();

        return $this->json(["status" => "ok", "message" => $message], 200);
    }

    /**
     * @Route("/account/check-last-login", name="account_check-last-login")
     */
    public function checkLastLogin(KernelInterface $kernel) {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:check-last-login',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();

        return new Response($content);
    }
}
