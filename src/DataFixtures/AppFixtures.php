<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductEdition;
use App\Entity\ProductFormat;
use App\Entity\ProductType;
use App\Services\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture {
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

        $productFormatB = new ProductFormat ();
        $productFormatB->setName("Libro");
        $productFormatB->addProductType($productTypeL);
        $manager->persist($productFormatB);

        $productFormat = new ProductFormat ();
        $productFormat->setName("Blue-ray + DVD");
        $productFormat->addProductType($productTypeP);
        $productFormat->addProductType($productTypeS);
        $manager->persist($productFormat);

        $productEditionP = new ProductEdition ();
        $productEditionP->setName("Pasta dura");
        $productEditionP->addProductType($productTypeL);
        $manager->persist($productEditionP);

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

        $user1 = $this->userService->createUser([
            "email" => "esau.egs@gmail.com",
            "password" => "password",
            "accountType" => "buyer",
        ]);

        $user2 = $this->userService->createUser([
            "email" => "esau.egs1@gmail.com",
            "password" => "password",
            "accountType" => "seller",
        ]);
        $this->userService->setLastLogin ($user1);
        $this->userService->setLastLogin ($user2);
        
        $product = new Product ();
        $product->setTitle("Harry Potter");
        $product->setDistribuitor("Panini");
        $product->setStock(20);
        $product->setPrice(2000);
        $product->setClicks(0);
        $product->setAuthor("Jesucristo and Homie's");
        $product->setYear("2018");
        $product->setGenero(['Terror']);
        $product->setVerified(false);
        $product->setIsVisible(false);
        $product->setPublisher($user2);
        $product->setVerificationComments("");
        $product->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed leo mauris. Duis sed nibh sollicitudin, efficitur turpis et, tempor turpis. Mauris vel elementum tortor. Nunc ac vehicula dui. Integer ultrices eget nunc at aliquam. Aenean venenatis tellus ut quam commodo egestas. Donec viverra massa est, id varius est imperdiet id. Integer aliquam mollis diam, quis sollicitudin odio. Proin augue augue, finibus eu euismod non, pretium sit amet elit. Donec enim felis, blandit at magna at, volutpat congue arcu.");
        $product->setProductType($productTypeL);
        $product->setImages(array (
            0 => array ('name' => '5ecb4de0db8a8.jpeg'),
            1 => array ('name' => '5ecb4de140954.jpeg'),
        ));
        $product->setFormat($productFormatB);
        $product->setEdition($productEditionP);
        $manager->persist($product);

        $product = new Product ();
        $product->setTitle("Harry Potter 2");
        $product->setDistribuitor("Panini");
        $product->setStock(20);
        $product->setPrice(3000);
        $product->setClicks(0);
        $product->setAuthor("Jesucristo and Homie's");
        $product->setYear("2018");
        $product->setGenero(['Terror']);
        $product->setVerified(false);
        $product->setIsVisible(false);
        $product->setPublisher($user2);
        $product->setVerificationComments("");
        $product->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed leo mauris. Duis sed nibh sollicitudin, efficitur turpis et, tempor turpis. Mauris vel elementum tortor. Nunc ac vehicula dui. Integer ultrices eget nunc at aliquam. Aenean venenatis tellus ut quam commodo egestas. Donec viverra massa est, id varius est imperdiet id. Integer aliquam mollis diam, quis sollicitudin odio. Proin augue augue, finibus eu euismod non, pretium sit amet elit. Donec enim felis, blandit at magna at, volutpat congue arcu.");
        $product->setProductType($productTypeL);
        $product->setImages(array (
            0 => array ('name' => '5ecb4de0db8a8.jpeg'),
            1 => array ('name' => '5ecb4de140954.jpeg'),
        ));
        $product->setFormat($productFormatB);
        $product->setEdition($productEditionP);
        $manager->persist($product);

        $manager->flush();
    }
}
