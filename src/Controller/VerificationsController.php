<?php

namespace App\Controller;

use App\Entity\Verifications;
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
    public function verificate(EntityManagerInterface $em, Request $request) {
        $id = $request->request->get('id', null);
        $comments = $request->request->get('verComments', null);
        $approved = $request->request->get('approved', null);
        dump("Ola");
        $verification = $em->getRepository(Verifications::class)->findOneBy(["id" => $id]);
        dump($verification);
        dump($id);
        if (!isset($verification)) {
            return $this->json(["status" => "error", "message" => "Este producto no ha sido solicitado para ser verificado"], 406);
        }
        $product = $verification->getProduct();
        $product->setVerificationComments($comments);
        $product->setVerified($approved);
        dump("Adio");
        $em->persist($product);
        $em->flush();
        $em->remove($verification);
        $em->flush();
        dump("XD");
        
        return $this->json(["status" => "ok"], 200);
    }
}
