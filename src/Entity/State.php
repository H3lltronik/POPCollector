<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StateRepository::class)
 */
class State
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
     * @ORM\OneToMany(targetEntity=ShippingAddress::class, mappedBy="state")
     */
    private $shippingAddresses;

    public function __construct()
    {
        $this->shippingAddresses = new ArrayCollection();
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
     * @return Collection|ShippingAddress[]
     */
    public function getShippingAddresses(): Collection
    {
        return $this->shippingAddresses;
    }

    public function addShippingAddress(ShippingAddress $shippingAddress): self
    {
        if (!$this->shippingAddresses->contains($shippingAddress)) {
            $this->shippingAddresses[] = $shippingAddress;
            $shippingAddress->setState($this);
        }

        return $this;
    }

    public function removeShippingAddress(ShippingAddress $shippingAddress): self
    {
        if ($this->shippingAddresses->contains($shippingAddress)) {
            $this->shippingAddresses->removeElement($shippingAddress);
            // set the owning side to null (unless already changed)
            if ($shippingAddress->getState() === $this) {
                $shippingAddress->setState(null);
            }
        }

        return $this;
    }
}
