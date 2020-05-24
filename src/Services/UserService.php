<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService {
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder) {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->accountRoles = [
            "buyer" => ["ROLE_BUYER"],
            "seller" => ["ROLE_SELLER"],
            "checker" => ["ROLE_CHECKER"],
            "admin" => ["ROLE_ADMIN"]
        ];
    }

    public function getRoles(string $accountType) {
        return $this->accountRoles[$accountType];
    }

    public function createUser ($values) {
        $user = new User();
        $this->em->persist($user);

        $user->setEmail($values['email']);
        $password = $values['password'];
        if ($password) {
            $user->setPassword($this->encoder->encodePassword($user, $password));
        }

        $roles = $this->getRoles($values['accountType']);
        $user->setRoles($roles ?? []);

        $this->em->flush();
    }

    public function mainMenuOptByUser(?User $user) {
        if (!isset($user))
            return [];
            
        $opts = [
            "0" => [
                "href" => "/",
                "text" => "Cuenta",
            ],
            "logout" => [
                "href" => "/logout",
                "text" => "Cerrar sesion",
            ],
        ];

        if (in_array("ROLE_BUYER", $user->getRoles())) {
            array_push($opts, [
                "href" => "/",
                "text" => "Estadisticas",
            ]);
        } else if (in_array("ROLE_SELLER", $user->getRoles())) {
            array_push($opts, [
                "href" => "/product/create/categories",
                "text" => "Publicar producto",
            ],
            [
                "href" => "/",
                "text" => "Estadisticas",
            ]);
        }
        return $opts;
        
    }
}