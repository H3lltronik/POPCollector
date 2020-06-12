<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductType;
use App\Entity\ProductFormat;
use App\Services\UserService;
use App\Entity\ProductEdition;
use App\Entity\Personalization;
use App\Entity\ShippingAddress;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppBFixtures extends Fixture {
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

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
        $productTypeP->setName("Películas");
        $manager->persist($productTypeP);

        $productTypeC = new ProductType ();
        $productTypeC->setName("CD's");
        $manager->persist($productTypeC);

        $productTypeV = new ProductType ();
        $productTypeV->setName("Vinilos");
        $manager->persist($productTypeV);

        $productFormatB = new ProductFormat ();
        $productFormatB->setName("Impreso");
        $productFormatB->addProductType($productTypeL);
        $manager->persist($productFormatB);

        $productFormat = new ProductFormat ();
        $productFormat->setName("CD");
        $productFormat->addProductType($productTypeC);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("2 CD's");
        $productFormat->addProductType($productTypeC);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("+2 Discos");
        $productFormat->addProductType($productTypeC);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("CD y DVD");
        $productFormat->addProductType($productTypeC);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("Blu-ray");
        $productFormat->addProductType($productTypeP);
        $productFormat->addProductType($productTypeS);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("DVD");
        $productFormat->addProductType($productTypeP);
        $productFormat->addProductType($productTypeS);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("4K");
        $productFormat->addProductType($productTypeP);
        $productFormat->addProductType($productTypeS);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("Blu-ray + DVD");
        $productFormat->addProductType($productTypeP);
        $productFormat->addProductType($productTypeS);
        $manager->persist($productFormat);

        $productFormat = new ProductFormat ();
        $productFormat->setName("Vinilo");
        $productFormat->addProductType($productTypeV);
        $manager->persist($productFormat);

        $productEditionP = new ProductEdition ();
        $productEditionP->setName("Pasta dura");
        $productEditionP->addProductType($productTypeL);
        $manager->persist($productEditionP);

        $productEditionP = new ProductEdition ();
        $productEditionP->setName("Pasta blanda");
        $productEditionP->addProductType($productTypeL);
        $manager->persist($productEditionP);

        $productEditionP = new ProductEdition ();
        $productEditionP->setName("Debolsillo");
        $productEditionP->addProductType($productTypeL);
        $manager->persist($productEditionP);

        $productEditionP = new ProductEdition ();
        $productEditionP->setName("Solapas");
        $productEditionP->addProductType($productTypeL);
        $manager->persist($productEditionP);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Box set");
        $productEdition->addProductType($productTypeL);
        $productEdition->addProductType($productTypeP);
        $productEdition->addProductType($productTypeS);
        $manager->persist($productEdition);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Slip cover");
        $productEdition->addProductType($productTypeP);
        $productEdition->addProductType($productTypeS);
        $manager->persist($productEdition);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Steelbook");
        $productEdition->addProductType($productTypeP);
        $productEdition->addProductType($productTypeS);
        $manager->persist($productEdition);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Estándar");
        $productEdition->addProductType($productTypeP);
        $productEdition->addProductType($productTypeS);
        $manager->persist($productEdition);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Colección");
        $productEdition->addProductType($productTypeP);
        $productEdition->addProductType($productTypeS);
        $manager->persist($productEdition);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Cartón");
        $productEdition->addProductType($productTypeC);
        $productEdition->addProductType($productTypeV);
        $manager->persist($productEdition);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Cristal");
        $productEdition->addProductType($productTypeC);
        $manager->persist($productEdition);

        $productEdition = new ProductEdition ();
        $productEdition->setName("Otro");        
        $productEdition->addProductType($productTypeV);
        $manager->persist($productEdition);                      

        $manager->flush();
    }
}
