<?php

namespace App\Entity;

use App\Repository\ProductFormatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToMany(targetEntity=ProductType::class, inversedBy="productFormats")
     */
    private $ProductType;

    public function __construct()
    {
        $this->ProductType = new ArrayCollection();
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
}
