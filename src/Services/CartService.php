<?php

namespace App\Services;

use App\Entity\Sale;
use App\Entity\Product;
use App\Entity\Ticket;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {
    public function __construct(SessionInterface $session, EntityManagerInterface $em, Security $security) {
        $this->session = $session;
        $this->security = $security;
        $this->em = $em;

        if (!$this->session->isStarted()) {
            $this->session->start();
        }

    }

    public function addToCart($values) {
        $cart = [];
        $inArray = false;

        // $this->session->set('cart', []);

        if ($this->session->get('cart')) {
            $cart = $this->session->get('cart');
        }

        // Restricciones
        if ($values['quantity'] < 1 || !isset($values)) {
            throw "error";
        }

        foreach($cart as $key => $it) {
            if ($it['idProduct'] == $values['idProduct']) {
                $sum = $it['quantity'] + $values['quantity'];
                $cart[$key]['quantity'] = $sum;

                $inArray = true;
            }   
        }

        if(!$inArray) {
            array_push($cart, $values);
        }
        
        $this->session->set('cart', $cart);
    }

    public function getCartData() {
        $cart = [];
        $products = [];

        if ($this->session->get('cart')) {
            $cart = $this->session->get('cart');
        }

        foreach ($cart as $key=>$product) {
            $productFound = $this->em->getRepository(Product::class)->findOneBy(["id" => $product['idProduct']]);
            $products[$key]["product"] = $productFound;
            $products[$key]["id"] = $productFound->getId();
            $products[$key]["quantity"] = $product["quantity"];
            $products[$key]["total"] = "$".$productFound->getPrice() * $product["quantity"];
            $products[$key]["totalPrice"] = $productFound->getPrice() * $product["quantity"];
        }

        return $products;
    }

    public function removeFromCart($idProduct) {
        $cart = [];

        if ($this->session->get('cart')) {
            $cart = $this->session->get('cart');
        }

        $result = array_filter(
            $cart, 
            function ($arrValue) use($idProduct) {
                return ($arrValue["idProduct"] != $idProduct);
            }
        );

        $this->session->set('cart', $result);
    }

    public function buyCart () {
        $cart = [];
        $user = $this->security->getUser();

        if ($this->session->get('cart')) {
            $cart = $this->session->get('cart');
        }

        if (sizeof($cart) <= 0)
            return;

        $ticket = new Ticket ();
        $ticket->setCreatedAt(new DateTime("now"));
        $ticket->setUser($user);

        foreach ($cart as $cartProduct) {
            $product = $this->em->getRepository(Product::class)->findOneBy(["id" => $cartProduct['idProduct']]);
            $stock = $product->getStock();
            $stock -= $cartProduct["quantity"];

            if ($stock < 0) {
                throw "Not enough stock!";
            }

            $total = $cartProduct["quantity"] * $product->getPrice();

            $product->setStock($stock);

            $sale = new Sale ();
            $sale->setProduct($product);
            $sale->setTicket($ticket);
            $sale->setCreatedAt(new DateTime("now"));
            $sale->setQuantity($cartProduct["quantity"]);
            $sale->setPrice($total);

            $this->em->persist($product);
            $this->em->persist($sale);
        }
        $this->em->persist($ticket);

        $this->em->flush();
        $this->session->set('cart', []);
    }
    
}