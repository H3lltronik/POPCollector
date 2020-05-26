<?php

namespace App\Controller;

use App\Services\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController {

    /**
     * @Route("/cart/add", name="cart_add", methods={"POST"})
     */
    public function index(Request $request, CartService $cartService) {
        $values = [];
        $values['idProduct'] = $request->request->get("idProduct", null);
        $values['quantity'] = $request->request->get("quantity", null);
        $cartService->addToCart($values);

        return JsonResponse::create(["status" => "ok"]);
    }

    /**
     * @Route("/cart/remove", name="cart_remove", methods={"POST"})
     */
    public function remove(Request $request, CartService $cartService) {
        $id = $request->request->get("idProduct", null);
        $cartService->removeFromCart($id);

        return JsonResponse::create(["status" => "ok"]);
    }

    /**
     * @Route("/cart/buy", name="cart_buy", methods={"POST"})
     */
    public function buy(Request $request, CartService $cartService) {
        $cartService->buyCart();

        return JsonResponse::create(["status" => "ok"]);
    }
}
