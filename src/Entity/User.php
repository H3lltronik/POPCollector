<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="user")
     */
    private $tickets;

    /**
     * @ORM\OneToOne(targetEntity=WishList::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $wishList;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="publisher")
     */
    private $products;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="boolean", options={"default": 1})
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=SeBusca::class, mappedBy="publisher")
     */
    private $seBuscas;

    /**
     * @ORM\OneToOne(targetEntity=Personalization::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $personalization;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->seBuscas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setUser($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getUser() === $this) {
                $ticket->setUser(null);
            }
        }

        return $this;
    }

    public function getWishList(): ?WishList
    {
        return $this->wishList;
    }

    public function setWishList(?WishList $wishList): self
    {
        $this->wishList = $wishList;

        // set (or unset) the owning side of the relation if necessary
        $newUser = null === $wishList ? null : $this;
        if ($wishList->getUser() !== $newUser) {
            $wishList->setUser($newUser);
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
            $product->setPublisher($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getPublisher() === $this) {
                $product->setPublisher(null);
            }
        }

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|SeBusca[]
     */
    public function getSeBuscas(): Collection
    {
        return $this->seBuscas;
    }

    public function addSeBusca(SeBusca $seBusca): self
    {
        if (!$this->seBuscas->contains($seBusca)) {
            $this->seBuscas[] = $seBusca;
            $seBusca->setPublisher($this);
        }

        return $this;
    }

    public function removeSeBusca(SeBusca $seBusca): self
    {
        if ($this->seBuscas->contains($seBusca)) {
            $this->seBuscas->removeElement($seBusca);
            // set the owning side to null (unless already changed)
            if ($seBusca->getPublisher() === $this) {
                $seBusca->setPublisher(null);
            }
        }

        return $this;
    }

    public function getPersonalization(): ?Personalization
    {
        return $this->personalization;
    }

    public function setPersonalization(?Personalization $personalization): self
    {
        $this->personalization = $personalization;

        // set (or unset) the owning side of the relation if necessary
        $newUser = null === $personalization ? null : $this;
        if ($personalization->getUser() !== $newUser) {
            $personalization->setUser($newUser);
        }

        return $this;
    }
}
