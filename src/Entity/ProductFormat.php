<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use App\Repository\ProductFormatRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ProductFormatRepository::class)
 */
class ProductFormat
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
     * @ORM\ManyToMany(targetEntity=ProductType::class, inversedBy="productFormats")
     */
    private $ProductType;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="format")
     */
    private $products;

    public function __construct()
    {
        $this->ProductType = new ArrayCollection();
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
        return $this->ProductType;
    }

    public function addProductType(ProductType $productType): self
    {
        if (!$this->ProductType->contains($productType)) {
            $this->ProductType[] = $productType;
        }

        return $this;
    }

    public function removeProductType(ProductType $productType): self
    {
        if ($this->ProductType->contains($productType)) {
            $this->ProductType->removeElement($productType);
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
            $product->setFormat($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getFormat() === $this) {
                $product->setFormat(null);
            }
        }

        return $this;
    }
}
