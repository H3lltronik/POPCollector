<?php

namespace App\Services;

use DateTime;
use DateTimeZone;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService {
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, Security $security) {
        $this->em = $em;
        $this->security = $security;
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

        if ($this->hasRole(["ROLE_BUYER", "ROLE_ADMIN"])) {
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
        } 
        if ($this->hasRole(["ROLE_SELLER", "ROLE_ADMIN"])) {
            array_push($opts, [
                "href" => "/product/create/categories",
                "text" => "Publicar producto",
            ],
            [
                "href" => "/statistics",
                "text" => "Estadisticas",
            ]);
        } 
        if ($this->hasRole(["ROLE_VERIFICATOR", "ROLE_ADMIN"])) {
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

    public function hasRole(array $roles) {
        $user = $this->security->getUser();
        if (!isset($user)) {
            return false;
        }
        $userRoles = $user->getRoles();

        if (count(array_intersect($userRoles, $roles)) > 0) {
            return true;
        } else {
            return false;
        }
    }
}