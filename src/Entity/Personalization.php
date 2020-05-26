<?php

namespace App\Entity;

use App\Repository\PersonalizationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonalizationRepository::class)
 */
class Personalization
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $father_last_name;

    /**
     * @ORM\Column(type="text")
     */
    private $mother_last_name;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="personalization", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=ShippingAddress::class, mappedBy="personalization", cascade={"persist", "remove"})
     */
    private $shippingAddress;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFatherLastName(): ?string
    {
        return $this->father_last_name;
    }

    public function setFatherLastName(string $father_last_name): self
    {
        $this->father_last_name = $father_last_name;

        return $this;
    }

    public function getMotherLastName(): ?string
    {
        return $this->mother_last_name;
    }

    public function setMotherLastName(string $mother_last_name): self
    {
        $this->mother_last_name = $mother_last_name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getShippingAddress(): ?ShippingAddress
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?ShippingAddress $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        // set (or unset) the owning side of the relation if necessary
        $newPersonalization = null === $shippingAddress ? null : $this;
        if ($shippingAddress->getPersonalization() !== $newPersonalization) {
            $shippingAddress->setPersonalization($newPersonalization);
        }

        return $this;
    }
}
