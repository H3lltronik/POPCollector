<?php

namespace App\Controller;

use DateTime;
use App\Entity\Ticket;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TicketController extends AbstractController
{
    /**
     * @Route("/ticket", name="ticket")
     */
    public function load(Request $request, EntityManagerInterface $em, SerializerInterface $serializer) {
        $id = $request->request->get('idTicket', 1);
        $ticket = $em->getRepository(Ticket::class)->findOneBy(["id" => $id]);

        $json = $serializer->serialize($ticket, 'json');

        return JsonResponse::create(["status" => "ok", "ticket" => $json]);
    }

    /**
     * @Route("/ticket/save", name="ticket_save")
     */
    public function save(Request $request, EntityManagerInterface $em) {
        $status = $request->request->get('status', null);
        $rastreo = $request->request->get('rastreo', null);
        $id = $request->request->get('idTicket', 1);
        $ticket = $em->getRepository(Ticket::class)->findOneBy(["id" => $id]);

        if (isset($status)) {
            $ticket->setStatus($status);
        }
        if (isset($rastreo)) {
            $ticket->setRastreo($rastreo);
        }
        $ticket->setUpdatedAt(new DateTime("now"));

        $em->persist($ticket);
        $em->flush();

        return JsonResponse::create(["status" => "ok"]);
    }
}
