<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase implements JsonSerializable
{
    /** @var int|null  */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** @var string|null  */
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $amount = null;

    /** @var DateTimeInterface|null  */
    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $purchaseDate = null;

    /** @var integer */
    #[ORM\Column(type: "integer")]
    private int $quantity;

    /** @var User|null  */
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: "purchases")]
    private Collection $products;

    public function __construct() {
        $this->products = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return $this
     */
    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getPurchaseDate(): ?DateTimeInterface
    {
        return $this->purchaseDate;
    }

    /**
     * @param DateTimeInterface $purchaseDate
     * @return $this
     */
    public function setPurchaseDate(DateTimeInterface $purchaseDate): self
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     * @param Product $products
     * @return $this
     */
    public function setQuantity(?int $quantity, Product $products): self
    {
        $this->quantity = $quantity;
        if ($this->quantity) {
            $this->amount = $products->getPrice() * $this->quantity;
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @param Collection $products
     * @return $this
     */
    public function setProducts(Collection $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addPurchase($this);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "purchaseDate" => $this->getPurchaseDate(),
            "product" => $this->getProducts()->toArray(),
            "quantity" => $this->getQuantity(),
            "amount" => $this->getAmount()
        ];
    }
}