<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\ProductService;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WishListController extends AbstractController {

    public function __construct(ProductService $productService, PaginatorInterface $paginator, 
    EntityManagerInterface $em, Security $security) {
        $this->productService = $productService;
        $this->paginator = $paginator;
        $this->em = $em;
        $this->security = $security;
    }
    /**
     * @Route("/user/wishlist", name="wish_list")
     */
    public function index(Request $request) {

        return $this->render('wish_list/index.html.twig', [
        ]);
    }
}
