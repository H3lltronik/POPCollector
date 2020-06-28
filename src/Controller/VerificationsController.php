<?php

namespace App\Controller;

use App\Entity\Verifications;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VerificationsController extends AbstractController
{
    /**
     * @Route("/verifications", name="verifications")
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request) {
        $page = $request->query->get('page', 1);
        $query = $em->getRepository(Verifications::class)->createQueryBuilder("e");
        $pagination = $paginator->paginate($query, $page, 16);
        
        return $this->render('verifications/index.html.twig', [
            "verifications" => $pagination->getItems(),
            "pagination" => $pagination,
        ]);
    }

    /**
     * @Route("/verificate", name="product_verificate", methods={"POST"})
     */
    public function verificate(EntityManagerInterface $em, Request $request, UserService $userService) {
        $id = $request->request->get('id', null);
        $comments = $request->request->get('verComments', null);
        $approved = $request->request->get('approved', null);
        $verification = $em->getRepository(Verifications::class)->findOneBy(["id" => $id]);

        if (!isset($verification)) {
            return $this->json(["status" => "error", "message" => "Este producto no ha sido solicitado para ser verificado"], 406);
        }

        $product = $verification->getProduct();
        $product->setVerificationComments($comments);
        $product->setVerified($approved);
        if ($approved == 'false') {
            $publisher = $product->getPublisher();
            $currStrikes = $publisher->getStrikes() + 1;
            $publisher->setStrikes($currStrikes);
            $em->persist($publisher);

            if ($currStrikes == 2) {
                $userService->notifyUser($publisher, "verificationsBlock");
                dump("Se ha notificado al usuario acerca de su comportamiento");
            } else if ($currStrikes >= 3) {
                $publisher->setIsActive(false);
                $userService->notifyUser($publisher, "blocked");
                dump("Se ha bloqueado al usuario :(");
            }
        }
        $em->persist($product);
        $em->remove($verification);
        $em->flush();
        
        return $this->json(["status" => "ok"], 200);
    }
}
