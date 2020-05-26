<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use JMS\Serializer\Annotation\Exclude;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $distribuitor;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $year;

    /**
     * @ORM\Column(type="json")
     */
    private $genero = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $verified;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $verificationComments;

    /**
     * @ORM\OneToMany(targetEntity=Sale::class, mappedBy="product")
     */
    private $sales;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=ProductType::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productType;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $images = [];

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity=ProductFormat::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $format;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity=ProductEdition::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $edition;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity=WishList::class, inversedBy="products")
     */
    private $wishList;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publisher;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $isVisible;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity=WishList::class, mappedBy="products")
     */
    private $wishLists;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default": 0})
     */
    private $clicks;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity=SeBusca::class, mappedBy="recommendations")
     */
    private $seBuscas;

    public function __construct()
    {
        $this->sales = new ArrayCollection();
        $this->wishLists = new ArrayCollection();
        $this->seBuscas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDistribuitor(): ?string
    {
        return $this->distribuitor;
    }

    public function setDistribuitor(?string $distribuitor): self
    {
        $this->distribuitor = $distribuitor;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getGenero(): ?array
    {
        return $this->genero;
    }

    public function setGenero(array $genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    public function getVerificationComments(): ?string
    {
        return $this->verificationComments;
    }

    public function setVerificationComments(?string $verificationComments): self
    {
        $this->verificationComments = $verificationComments;

        return $this;
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
            $sale->setProduct($this);
        }

        return $this;
    }

    public function removeSale(Sale $sale): self
    {
        if ($this->sales->contains($sale)) {
            $this->sales->removeElement($sale);
            // set the owning side to null (unless already changed)
            if ($sale->getProduct() === $this) {
                $sale->setProduct(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProductType(): ?ProductType
    {
        return $this->productType;
    }

    public function setProductType(?ProductType $productType): self
    {
        $this->productType = $productType;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getFormat(): ?ProductFormat
    {
        return $this->format;
    }

    public function setFormat(?ProductFormat $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getEdition(): ?ProductEdition
    {
        return $this->edition;
    }

    public function setEdition(?ProductEdition $edition): self
    {
        $this->edition = $edition;

        return $this;
    }

    public function getWishList(): ?WishList
    {
        return $this->wishList;
    }

    public function setWishList(?WishList $wishList): self
    {
        $this->wishList = $wishList;

        return $this;
    }

    /**
     * @VirtualProperty
     * @SerializedName("priceText")
     */
    public function getPriceText() {
        return "$".$this->getPrice();
    }

    /**
     * @VirtualProperty
     * @SerializedName("format")
     */
    public function getFormatID() {
        return $this->getFormat()->getId();
    }

    /**
     * @VirtualProperty
     * @SerializedName("edition")
     */
    public function getEditiontID() {
        return $this->getEdition()->getId();
    }

    public function getPublisher(): ?User
    {
        return $this->publisher;
    }

    public function setPublisher(?User $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * @return Collection|WishList[]
     */
    public function getWishLists(): Collection
    {
        return $this->wishLists;
    }

    public function addWishList(WishList $wishList): self
    {
        if (!$this->wishLists->contains($wishList)) {
            $this->wishLists[] = $wishList;
            $wishList->addProduct($this);
        }

        return $this;
    }

    public function removeWishList(WishList $wishList): self
    {
        if ($this->wishLists->contains($wishList)) {
            $this->wishLists->removeElement($wishList);
            $wishList->removeProduct($this);
        }

        return $this;
    }

    public function getClicks(): ?int
    {
        return $this->clicks;
    }

    public function setClicks(?int $clicks): self
    {
        $this->clicks = $clicks;

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
            $seBusca->addRecommendation($this);
        }

        return $this;
    }

    public function removeSeBusca(SeBusca $seBusca): self
    {
        if ($this->seBuscas->contains($seBusca)) {
            $this->seBuscas->removeElement($seBusca);
            $seBusca->removeRecommendation($this);
        }

        return $this;
    }
}
