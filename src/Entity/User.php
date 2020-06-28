<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
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
     * @ORM\OneToOne(targetEntity=WishList::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $wishList;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="publisher")
     */
    private $products;

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

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_login;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="subscriptions")
     */
    private $subscriptions;

    /**
     * @ORM\OneToMany(targetEntity=Sale::class, mappedBy="seller")
     */
    private $sales;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="seller")
     */
    private $salesTicket;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="buyer")
     */
    private $purchasesTicket;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $strikes = 0;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->seBuscas = new ArrayCollection();
        $this->follower = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->sales = new ArrayCollection();
        $this->salesTicket = new ArrayCollection();
        $this->purchasesTicket = new ArrayCollection();
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

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->last_login;
    }

    public function setLastLogin(?\DateTimeInterface $last_login): self
    {
        $this->last_login = $last_login;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(self $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
        }

        return $this;
    }

    public function removeSubscription(self $subscription): self
    {
        if ($this->subscriptions->contains($subscription)) {
            $this->subscriptions->removeElement($subscription);
        }

        return $this;
    }

    /**
     * @VirtualProperty
     * @SerializedName("isAdmin")
     */
    public function isAdmin() {
        $roles = $this->getRoles();
        if (in_array("ROLE_ADMIN", $roles))
            return true;
        else 
            return false;
    }

    /**
     * @VirtualProperty
     * @SerializedName("isSeller")
     */
    public function isSeller() {
        $roles = $this->getRoles();
        if (in_array("ROLE_SELLER", $roles))
            return true;
        else 
            return false;
    }

    /**
     * @VirtualProperty
     * @SerializedName("isBuyer")
     */
    public function isBuyer() {
        $roles = $this->getRoles();
        if (in_array("ROLE_BUYER", $roles))
            return true;
        else 
            return false;
    }

    /**
     * @return Collection|Sale[]
     */
    public function getSales(): Collection
    {
        return $this->sales;
    }

    public function addSale(Sale $sale): self
    {
        if (!$this->sales->contains($sale)) {
            $this->sales[] = $sale;
            $sale->setSeller($this);
        }

        return $this;
    }

    public function removeSale(Sale $sale): self
    {
        if ($this->sales->contains($sale)) {
            $this->sales->removeElement($sale);
            // set the owning side to null (unless already changed)
            if ($sale->getSeller() === $this) {
                $sale->setSeller(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getSalesTicket(): Collection
    {
        return $this->salesTicket;
    }

    public function addSalesTicket(Ticket $salesTicket): self
    {
        if (!$this->salesTicket->contains($salesTicket)) {
            $this->salesTicket[] = $salesTicket;
            $salesTicket->setSeller($this);
        }

        return $this;
    }

    public function removeSalesTicket(Ticket $salesTicket): self
    {
        if ($this->salesTicket->contains($salesTicket)) {
            $this->salesTicket->removeElement($salesTicket);
            // set the owning side to null (unless already changed)
            if ($salesTicket->getSeller() === $this) {
                $salesTicket->setSeller(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getPurchasesTicket(): Collection
    {
        return $this->purchasesTicket;
    }

    public function addPurchasesTicket(Ticket $purchasesTicket): self
    {
        if (!$this->purchasesTicket->contains($purchasesTicket)) {
            $this->purchasesTicket[] = $purchasesTicket;
            $purchasesTicket->setBuyer($this);
        }

        return $this;
    }

    public function removePurchasesTicket(Ticket $purchasesTicket): self
    {
        if ($this->purchasesTicket->contains($purchasesTicket)) {
            $this->purchasesTicket->removeElement($purchasesTicket);
            // set the owning side to null (unless already changed)
            if ($purchasesTicket->getBuyer() === $this) {
                $purchasesTicket->setBuyer(null);
            }
        }

        return $this;
    }

    public function getStrikes(): ?int
    {
        return $this->strikes;
    }

    public function setStrikes(int $strikes): self
    {
        $this->strikes = $strikes;

        return $this;
    }
}
