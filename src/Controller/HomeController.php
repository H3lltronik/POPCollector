<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\State;
use App\Entity\Personalization;
use App\Entity\ShippingAddress;
use App\Services\ProductService;
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
    public function index(ProductService $productService) {
        $latestProducts = $productService->getLatestsProducts ();
        return $this->render('home/index.html.twig', [
            'latestProducts' => $latestProducts,
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
        $userID = $request->request->get('id', null);
        $user = $em->getReference(User::class, $userID);
        $name = $request->request->get('name', null);
        $father = $request->request->get('father', null);
        $mother = $request->request->get('mother', null);
        $cp = $request->request->get('cp', null);
        $address = $request->request->get('address', null);
        $description = $request->request->get('description', null);
        $phone = $request->request->get('phone', null);
        $state = $request->request->get('state', null);

        $state = $em->getReference(State::class, $state);

        $personalizationID = $request->request->get('personalizationID', null);
        $shippingID = $request->request->get('shippingID', null);

        if (isset($personalizationID)) {
            $personalization = $em->getRepository(Personalization::class)->findOneBy(["id" => $personalizationID]);
        } else {
            $personalization = new Personalization ();
        }
        
        $personalization->setName($name);
        $personalization->setFatherLastName($father);
        $personalization->setMotherLastName($mother);
        $personalization->setUser($user);

        if (isset($shippingID)) {
            $shipping = $em->getRepository(ShippingAddress::class)->findOneBy(["id" => $shippingID]);
        } else {
            $shipping = new ShippingAddress ();
        }

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
