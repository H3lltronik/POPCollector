<?php

namespace App\Twig;

use Twig\Environment;
use Twig\TwigFunction;
use App\Entity\ProductType;
use App\Services\UserService;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FrontExtension extends AbstractExtension {
    private $templating;

    public function __construct(Environment $templating, EntityManagerInterface $em,
                                UserService $userService, RequestStack $requestStack) {
        $this->templating = $templating;
        $this->em = $em;
        $this->userService = $userService;
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array {
        return [
            new TwigFunction('render_toolbar', [$this, 'renderToolbar'], ['is_safe' => ['html']]),
        ];
    }

    public function renderToolbar($user) {
        $currentCategory = $this->requestStack->getCurrentRequest()->attributes->get('category') ?? null;
        // $query = $this->em->getRepository(Domain::class)->findByClientID($entity->getId(), ["name"]);
        $opts = $this->userService->mainMenuOptByUser($user) ?? [];
        $categories = $this->em->getRepository(ProductType::class)->findAll();

        $params = [
            "mainMenuOpts" => $opts,
            "user" => $user ?? null,
            "categories" => $categories,
            "currentCategory" => $currentCategory,
        ];

        return $this->templating->render('common/toolbar.html.twig', $params);
    }

}
