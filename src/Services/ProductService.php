<?php

namespace App\Services;

use DateTime;
use App\Entity\Product;
use App\Entity\WishList;
use App\Entity\ProductType;
use App\Entity\ProductFormat;
use App\Entity\ProductEdition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class ProductService {
    public function __construct(EntityManagerInterface $em, Security $security) {
        $this->em = $em;
        $this->security = $security;
    }

    public function createProduct(array $values) {
        $product = new Product();
        $product = $this->editBase($product, $values);
        $product->setIsVisible(true);

        $this->em->persist($product);
        $this->em->flush();
    }

    public function editProduct($productID, array $values) {
        $product = $this->em->getRepository(Product::class)->findOneBy(["id" => $productID]);
        $product = $this->editBase($product, $values);

        $images = $values['currImages'] ?? [];
        foreach ($values['images'] as $image) {
            array_push($images, $image);
        }
        $product->setImages($images);
        
        $user = $this->security->getUser();
        $product->setPublisher($user);

        $this->em->persist($product);
        $this->em->flush();
    }

    private function editBase (Product $product, array $values) {
        $product->setTitle($values['title']);
        $product->setDistribuitor($values['distribuitor']);
        $product->setStock($values['stock']);
        $product->setPrice($values['price']);
        $product->setAuthor($values['author']);
        $product->setYear($values['year']);
        $product->setGenero($values['generos']);
        $product->setDescription($values['description']);
        $product->setImages($values['images']);
        
        $user = $this->security->getUser();
        $product->setPublisher($user);

        // Relations
        $productTypeReq = $this->em->getRepository(ProductType::class)->findOneBy(["id" => $values['productType']]);
        $product->setProductType($productTypeReq);

        $productFormatReq = $this->em->getRepository(ProductFormat::class)->findOneBy(["id" => $values['productFormat']]);
        $product->setFormat($productFormatReq);

        $productEditionReq = $this->em->getRepository(ProductEdition::class)->findOneBy(["id" => $values['productEdition']]);
        $product->setEdition($productEditionReq);

        return $product;
    }

    public function getProductsByCategory($category) {
        $repo = $this->em->getRepository(Product::class);
        $query = $repo->createQueryBuilder("product");
        $query = $repo->addJoinTo($query, 'App\Entity\ProductType', "type", "product.productType");
        $query = $repo->addJoinTo($query, 'App\Entity\User', "publisher", "product.publisher");
        $query->andWhere("publisher.isActive = 1");
        $query->andWhere("type.Name = :val")->setParameter("val", $category);

        return $query;
    }

    public function getAllProducts() {
        $repo = $this->em->getRepository(Product::class);
        $query = $repo->createQueryBuilder("product");

        return $query;
    }

    public function getRelatedProducts($category, $quantity, $excludeID) {
        $query = $this->getProductsByCategory ($category);
        $query->orderBy('product.id', 'ASC');
        $products = $query->getQuery()->getResult();
        $productsRelated = [];
        $auxCont = 0;

        if (!sizeof($products) > 2) {
            return [];
        }

        foreach ($products as $product) {
            $numbers[] = $product->getId();
            if ($auxCont++ > $quantity)
                break;
        }
        $numbers = array_diff($numbers, array($excludeID));

        foreach ($numbers as $number) {
            $productsRelated[] = $this->em->getRepository(Product::class)->findOneBy(["id" => $number]);
        }
        dump($productsRelated);

        return $productsRelated;
    }

    public function getLatestsProducts() {
        $repo = $this->em->getRepository(Product::class);
        $query = $repo->createQueryBuilder("product");
        $query = $repo->addJoinTo($query, 'App\Entity\User', "publisher", "product.publisher");
        $query->andWhere("publisher.isActive = 1");
        $query->andWhere("product.isVisible = 1");
        $query->orderBy('product.id', 'DESC');
        $query->setMaxResults(16);
        $products = $query->getQuery()->getResult();
        
        dump($products);

        return $products;
    }

    public function addProductToWishlist ($productID) {
        $product = $this->em->getRepository(Product::class)->findOneBy(["id" => $productID]);
        $user = $this->security->getUser();

        if ($user->getWishlist() === null) {
            $wishlist = new WishList ();
            $wishlist->setUser($user);
            $wishlist->setCreatedAt(new DateTime("now"));
        } else {
            $wishlist = $user->getWishlist();
        }

        $wishlist->addProduct($product);

        $this->em->persist($wishlist);
        $this->em->flush();
    }

    public function checkProductClicks(KernelInterface $kernel) {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:check-products-clicks',
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();

        // return new Response(""), if you used NullOutput()
        return new Response($content);
    }
}