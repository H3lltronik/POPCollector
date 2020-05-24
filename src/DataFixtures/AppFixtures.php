<?php

namespace App\DataFixtures;

use App\Entity\ProductEdition;
use App\Entity\ProductFormat;
use App\Entity\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture {
    public function load(ObjectManager $manager) {
        // $product = new Product();
        // $manager->persist($product);

        $productTypeL = new ProductType ();
        $productTypeL->setName("Libros");
        $manager->persist($productTypeL);

        $productTypeS = new ProductType ();
        $productTypeS->setName("Series");
        $manager->persist($productTypeS);

        $productTypeP = new ProductType ();
        $productTypeP->setName("Peliculas");
        $manager->persist($productTypeP);

        $productTypeC = new ProductType ();
        $productTypeC->setName("CD's");
        $manager->persist($productTypeC);

        $productTypeV = new ProductType ();
        $productTypeV->setName("Vinilos");
        $manager->persist($productTypeV);

        $productFormat = new ProductFormat ();
        $productFormat->setName("2 CD's");
        $productFormat->addProductType($productTypeC);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("+2 Discos");
        $productFormat->addProductType($productTypeC);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("4K");
        $productFormat->addProductType($productTypeP);
        $productFormat->addProductType($productTypeS);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("DVD");
        $productFormat->addProductType($productTypeP);
        $productFormat->addProductType($productTypeS);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("Vinilo");
        $productFormat->addProductType($productTypeV);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("Blue-ray + DVD");
        $productFormat->addProductType($productTypeP);
        $productFormat->addProductType($productTypeS);
        $manager->persist($productFormat);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Pasta dura");
        $productEdition->addProductType($productTypeL);
        $manager->persist($productEdition);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Box set");
        $productEdition->addProductType($productTypeL);
        $productEdition->addProductType($productTypeP);
        $productEdition->addProductType($productTypeS);
        $manager->persist($productEdition);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Coleccion");
        $productEdition->addProductType($productTypeP);
        $productEdition->addProductType($productTypeS);
        $manager->persist($productEdition);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Carton");
        $productEdition->addProductType($productTypeC);
        $productEdition->addProductType($productTypeV);
        $manager->persist($productEdition);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Cristal");
        $productEdition->addProductType($productTypeC);
        $productEdition->addProductType($productTypeV);
        $manager->persist($productEdition);

        $manager->flush();
    }
}
