<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use DateTimeInterface;
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

    #[ORM\Column(type: "integer")]
    private $quantity;

    /** @var User|null  */
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    /** @var Product|null  */
    #[ORM\ManyToOne(targetEntity: Product::class)]
    private ?Product $product = null;

    /** @var Category|null  */
    #[ORM\ManyToOne(targetEntity: Category::class)]
    private ?Category $category = null;

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
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return $this
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
     * @return $this
     */
    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        if ($this->product && $this->quantity) {
            $this->amount = $this->product->getPrice() * $this->quantity;
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
            "amount" => $this->getAmount(),
            "purchaseDate" => $this->getPurchaseDate(),
            "user" => $this->getUser(),
            "product" => $this->getProduct(),
            "category" => $this->getCategory(),
        ];
    }

}