<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppAStatesFixtures extends Fixture {
    public function load(ObjectManager $manager) {
        $state = new State();
        $state->setName("Aguascalientes");
        $manager->persist($state);
        
        $state = new State();
        $state->setName("Baja California");
        $manager->persist($state);

        $state = new State();
        $state->setName("Baja California Sur");
        $manager->persist($state);

        $state = new State();
        $state->setName("Campeche");
        $manager->persist($state);

        $state = new State();
        $state->setName("Chiapas");
        $manager->persist($state);

        $state = new State();
        $state->setName("Chihuahua");
        $manager->persist($state);

        $state = new State();
        $state->setName("Coahuila");
        $manager->persist($state);

        $state = new State();
        $state->setName("Colima");
        $manager->persist($state);

        $state = new State();
        $state->setName("Distrito Federal");
        $manager->persist($state);

        $state = new State();
        $state->setName("Durango");
        $manager->persist($state);

        $state = new State();
        $state->setName("Guanajuato");
        $manager->persist($state);

        $state = new State();
        $state->setName("Guerrero");
        $manager->persist($state);

        $state = new State();
        $state->setName("Hidalgo");
        $manager->persist($state);

        $state = new State();
        $state->setName("Jalisco");
        $manager->persist($state);

        $state = new State();
        $state->setName("México");
        $manager->persist($state);

        $state = new State();
        $state->setName("Michoacán");
        $manager->persist($state);

        $state = new State();
        $state->setName("Morelos");
        $manager->persist($state);

        $state = new State();
        $state->setName("Nayarit");
        $manager->persist($state);

        $state = new State();
        $state->setName("Nuevo León");
        $manager->persist($state);

        $state = new State();
        $state->setName("Oaxaca");
        $manager->persist($state);

        $state = new State();
        $state->setName("Puebla");
        $manager->persist($state);

        $state = new State();
        $state->setName("Querétaro");
        $manager->persist($state);

        $state = new State();
        $state->setName("Quintana Roo");
        $manager->persist($state);

        $state = new State();
        $state->setName("San Luis Potosí");
        $manager->persist($state);

        $state = new State();
        $state->setName("Sinaloa");
        $manager->persist($state);

        $state = new State();
        $state->setName("Sonora");
        $manager->persist($state);

        $state = new State();
        $state->setName("Tabasco");
        $manager->persist($state);

        $state = new State();
        $state->setName("Tamaulipas");
        $manager->persist($state);

        $state = new State();
        $state->setName("Tlaxcala");
        $manager->persist($state);

        $state = new State();
        $state->setName("Veracruz");
        $manager->persist($state);

        $state = new State();
        $state->setName("Zacatecas");
        $manager->persist($state);

        // $state = new State();
        // $state->setName("Yucatán");
        // $manager->persist($state);

        $manager->flush();
    }
}
