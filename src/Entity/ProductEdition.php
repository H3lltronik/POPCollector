<?php

namespace App\Entity;

use App\Repository\ProductEditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\Exclude;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductEditionRepository::class)
 */
class ProductEdition
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity=ProductType::class, inversedBy="productEditions")
     */
    private $productType;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="edition")
     */
    private $products;

    public function __construct()
    {
        $this->productType = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection|ProductType[]
     */
    public function getProductType(): Collection
    {
        return $this->productType;
    }

    public function addProductType(ProductType $productType): self
    {
        if (!$this->productType->contains($productType)) {
            $this->productType[] = $productType;
        }

        return $this;
    }

    public function removeProductType(ProductType $productType): self
    {
        if ($this->productType->contains($productType)) {
            $this->productType->removeElement($productType);
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setEdition($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getEdition() === $this) {
                $product->setEdition(null);
            }
        }

        return $this;
    }
}
