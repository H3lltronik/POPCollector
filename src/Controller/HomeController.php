<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="home")
 */
class HomeController extends AbstractController {
    /**
     * @Route("/", name="")
     */
    public function index() {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/crear-cuenta", name="_create_account")
     */
    public function createAccount() {
        return $this->render('security/create_account.html.twig');
    }

    /**
     * @Route("/test-flash", name="_flash")
     */
    public function test() {
        $this->addFlash('newAccount', 'Favor de iniciar sesion con tu nueva cuenta');
        return $this->forward("App\Controller\SecurityController::login");
    }
}
