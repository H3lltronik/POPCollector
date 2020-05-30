<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/contact", name="contact")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("", name="")
     */
    public function index() {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    /**
     * @Route("/send", name="_send")
     */
    public function sendMail (Request $request, MailerInterface $mailer) {
        $motivo = $request->request->get('motive', null);
        $mensaje = $request->request->get('message', null);
        $emailT = $request->request->get('email', null);
        $name = $request->request->get('name', null);

            // return $this->json(["status" => "error", "message" => "Algunos de los datos proporcionados no son validos",
            // "email" => $emailT,
            // "name" => $name,
            // "motivo" => $motivo,
            // "mensaje" => $mensaje,], 500);

        $email = (new TemplatedEmail())
        ->from('no-reply@popcollector.com')
        ->to('esau.egs1@gmail.com')
        ->subject('Has recibido un nuevo correo de contacto')
        ->htmlTemplate('contact/mail.html.twig')
        ->context([
            'emailT' => $emailT,
            'name' => $name,
            'motive' => $motivo,
            'message' => $mensaje,
        ]);

        $mailer->send($email);

        return $this->json(["status" => "ok"], 200);
    }
}
