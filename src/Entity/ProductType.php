<?php

namespace App\Entity;

use App\Repository\ProductTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToMany(targetEntity=ProductFormat::class, mappedBy="ProductType")
     */
    private $productFormats;

    /**
     * @ORM\ManyToMany(targetEntity=ProductEdition::class, mappedBy="productType")
     */
    private $productEditions;

    public function __construct()
    {
        $this->productFormats = new ArrayCollection();
        $this->productEditions = new ArrayCollection();
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
}
