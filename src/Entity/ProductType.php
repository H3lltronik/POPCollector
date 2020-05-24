<?php

namespace App\Entity;

use App\Entity\Product;
use App\Entity\ProductFormat;
use App\Entity\ProductEdition;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use App\Repository\ProductTypeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ProductTypeRepository::class)
 */
class ProductType
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
     * @ORM\ManyToMany(targetEntity=ProductFormat::class, mappedBy="ProductType")
     */
    private $productFormats;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity=ProductEdition::class, mappedBy="productType")
     */
    private $productEditions;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="productType")
     */
    private $products;

    public function __construct()
    {
        $this->productFormats = new ArrayCollection();
        $this->productEditions = new ArrayCollection();
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
     * @return Collection|ProductFormat[]
     */
    public function getProductFormats(): Collection
    {
        return $this->productFormats;
    }

    public function addProductFormat(ProductFormat $productFormat): self
    {
        if (!$this->productFormats->contains($productFormat)) {
            $this->productFormats[] = $productFormat;
            $productFormat->addProductType($this);
        }

        return $this;
    }

    public function removeProductFormat(ProductFormat $productFormat): self
    {
        if ($this->productFormats->contains($productFormat)) {
            $this->productFormats->removeElement($productFormat);
            $productFormat->removeProductType($this);
        }

        return $this;
    }

    /**
     * @return Collection|ProductEdition[]
     */
    public function getProductEditions(): Collection
    {
        return $this->productEditions;
    }

    public function addProductEdition(ProductEdition $productEdition): self
    {
        if (!$this->productEditions->contains($productEdition)) {
            $this->productEditions[] = $productEdition;
            $productEdition->addProductType($this);
        }

        return $this;
    }

    public function removeProductEdition(ProductEdition $productEdition): self
    {
        if ($this->productEditions->contains($productEdition)) {
            $this->productEditions->removeElement($productEdition);
            $productEdition->removeProductType($this);
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
            $product->setProductType($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getProductType() === $this) {
                $product->setProductType(null);
            }
        }

        return $this;
    }
}
