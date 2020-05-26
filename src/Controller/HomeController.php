<?php

namespace App\Controller;

use App\Entity\State;
use App\Entity\Personalization;
use App\Entity\ShippingAddress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/personalization/save", name="_personalization_save")
     */
    public function savePersonalization(Request $request, EntityManagerInterface $em, Security $security) {
        $user = $security->getUser();
        $name = $request->request->get('name', null);
        $father = $request->request->get('father', null);
        $mother = $request->request->get('mother', null);
        $cp = $request->request->get('cp', null);
        $address = $request->request->get('address', null);
        $description = $request->request->get('description', null);
        $phone = $request->request->get('phone', null);
        $state = $request->request->get('state', null);

        $state = $em->getRepository(State::class)->findOneBy(["id" => $state]);

        $personalization = new Personalization ();

        $personalization->setName($name);
        $personalization->setFatherLastName($father);
        $personalization->setMotherLastName($mother);
        $personalization->setUser($user);

        $shipping = new ShippingAddress ();
        $shipping->setAddress($address);
        $shipping->setDescription($description);
        $shipping->setPersonalization($personalization);
        $shipping->setPhone($phone);
        $shipping->setPostalCode(intval($cp));
        $shipping->setState($state);

        $em->persist($personalization);
        $em->persist($shipping);

        $em->flush();
        
        return JsonResponse::create(["status" => "ok"]);
    }
}
