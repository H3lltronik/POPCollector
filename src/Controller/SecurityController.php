<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/save",name="app_user_save")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */
    public function save( Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder) {
		// $this->security->denyUnlessHasRole("ROLE_USERS_WRITE");

        $user = new User();
        $em->persist($user);

        $user->setEmail($request->request->get('email'));
        $password = $request->request->get('password');
        if ($password) {
            $user->setPassword($encoder->encodePassword($user, $password));
        }

        $em->flush();

        // $this->addFlash('newAccount', 'Favor de iniciar sesion con tu nueva cuenta');
        return JsonResponse::create(['status' => 'ok']);
    }
}
