<?php

namespace App\Twig;

use Twig\Environment;
use Twig\TwigFunction;
use App\Entity\ProductType;
use App\Entity\State;
use App\Services\CartService;
use App\Services\UserService;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FrontExtension extends AbstractExtension {
    private $templating;

    public function __construct(Environment $templating, EntityManagerInterface $em,
                                UserService $userService, RequestStack $requestStack,
                                CartService $cartService) {
        $this->templating = $templating;
        $this->em = $em;
        $this->userService = $userService;
        $this->requestStack = $requestStack;
        $this->cartService = $cartService;
    }

    public function getFunctions(): array {
        return [
            new TwigFunction('render_toolbar', [$this, 'renderToolbar'], ['is_safe' => ['html']]),
            new TwigFunction('render_personalization', [$this, 'renderPersonalization'], ['is_safe' => ['html']]),
            new TwigFunction('render_product', [$this, 'renderProduct'], ['is_safe' => ['html']]),
        ];
    }

    public function renderToolbar($user) {
        $currentCategory = $this->requestStack->getCurrentRequest()->attributes->get('category') ?? null;
        // $query = $this->em->getRepository(Domain::class)->findByClientID($entity->getId(), ["name"]);
        $opts = $this->userService->mainMenuOptByUser($user) ?? [];
        $categories = $this->em->getRepository(ProductType::class)->findAll();

        $cart = $this->cartService->getCartData ();
        dump($currentCategory);

        $params = [
            "mainMenuOpts" => $opts,
            "user" => $user ?? null,
            "categories" => $categories,
            "cart" => $cart,
            "currentCategory" => $currentCategory,
        ];

        return $this->templating->render('common/toolbar.html.twig', $params);
    }

    public function renderPersonalization($user) {
        $states = $this->em->getRepository(State::class)->findAll();
        $header = "";
        $isseller = false;
        if (isset($user)) {
            $noPersonalization = ($user->getPersonalization() == null);

            if (in_array("ROLE_BUYER", $user->getRoles())) {
                $header = "Direccion de envio";
            } else if (in_array("ROLE_SELLER", $user->getRoles())) {
                $header = "Direccion de establecimiento";
                $isseller = true;
            }
        }
        else
            $noPersonalization = false;

        $params = [
            "states" => $states,
            "noPersonalization" => $noPersonalization,
            "header" => $header,
            "isseller" => $isseller,
        ];

        return $this->templating->render('common/personalization.html.twig', $params);
    }

    public function renderProduct($product) {
        return $this->templating->render('common/product.html.twig', [
            "product" => $product
        ]);
    }

}
