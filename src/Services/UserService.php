<?php

namespace App\Services;

use DateTime;
use DateTimeZone;
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
            "verificator" => ["ROLE_VERIFICATOR"],
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
        $user->setIsActive(true);

        $this->em->flush();

        return $user;
    }

    public function mainMenuOptByUser(?User $user) {
        if (!isset($user))
            return [];
            
        $opts = [
            "0" => [
                "href" => "/account",
                "text" => "Cuenta",
            ],
            "logout" => [
                "href" => "/logout",
                "text" => "Cerrar sesion",
            ],
        ];

        if (in_array("ROLE_BUYER", $user->getRoles())) {
            array_push($opts, [
                "href" => "/user/wishlist",
                "text" => "Wishlist",
            ],
            [
                "href" => "/se-busca/create",
                "text" => "Crear busqueda",
            ],
            [
                "href" => "/se-busca/list/own",
                "text" => "Ver busquedas",
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
        } else if (in_array("ROLE_VERIFICATOR", $user->getRoles())) {
            array_push($opts, [
                "href" => "/verifications",
                "text" => "Verificaciones",
            ]);
        }
        return $opts;
        
    }

    public function setLastLogin (User $user) {
        $user->setLastLogin(new DateTime("now", new DateTimeZone('America/Mexico_City') ));
        $this->em->persist($user);
        $this->em->flush();
    }
}