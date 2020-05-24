<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\ProductEdition;
use App\Entity\ProductFormat;
use App\Entity\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class ProductService {
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function createProduct(array $values) {
        $product = new Product();
        $product = $this->editBase($product, $values);

        $this->em->persist($product);
        $this->em->flush();
    }

    public function editProduct($productID, array $values) {
        $product = $this->em->getRepository(Product::class)->findOneBy(["id" => $productID]);
        $product = $this->editBase($product, $values);

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
        $product->setVerified(false);

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
        $query->andWhere("type.Name = :val")->setParameter("val", $category);

        return $query;
    }

    public function getAllProducts() {
        $repo = $this->em->getRepository(Product::class);
        $query = $repo->createQueryBuilder("product");

        return $query;
    }
}