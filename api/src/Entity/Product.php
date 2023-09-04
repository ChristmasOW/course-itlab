<?php

namespace App\Entity;

use JsonSerializable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use App\Validator\Constraints\ProductConstraint;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Action\CreateProductAction;
use App\EntityListener\ProductEntityListener;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => [
            "method" => "GET",
            "normalization_context" => ['groups' => ["get:collection:product"]]
        ],
        "post" => [
            "method" => "POST",
            "security" => "is_granted('" . User::ROLE_USER . "')",
            "denormalization_context" => ['groups' => ["post:collection:product"]],
            "normalization_context" => ['groups' => ["get:item:product"]],
            "controller" => CreateProductAction::class
        ]
    ],
    itemOperations: [
        "get" => [
            "method" => "GET",
            "normalization_context" => ['groups' => ["get:item:product"]]
        ],
        "put" => [
            "method" => "PUT",
            "security" => "is_granted('" . User::ROLE_ADMIN . "')"
        ],
        "delete" => [
            "method" => "DELETE",
            "security" => "is_granted('" . User::ROLE_ADMIN . "')"
        ]
    ],
    attributes: [
        "security" => "is_granted('" . User::ROLE_USER . "') or is_granted('" . User::ROLE_ADMIN . "')"
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    "name" => "partial",
    "description"
])]
#[ApiFilter(RangeFilter::class, properties: ["price"])]
#[ORM\EntityListeners([ProductEntityListener::class])]
#[ProductConstraint]
class Product
// implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:item:product"
    ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Your product name must have at least {{ limit }} characters long',
        maxMessage: 'Your product name cannot be longer that {{ limit }} characters',
    )]
    #[Groups([
        "get:collection:product",
        "get:item:product",
        "post:collection:product"
    ])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 2, scale: '0')]
    #[Assert\Positive]
    #[Assert\LessThanOrEqual(300)]
    #[Groups([
        "get:item:product",
        "post:collection:product"
    ])]
    private ?string $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Groups([
        "get:item:product",
        "post:collection:product"
    ])]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "products")]
    #[Groups([
        "get:item:product",
        "post:collection:product"
    ])]
    private ?Category $category = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "products")]
    #[Groups([
        "get:item:product",
        "post:collection:product"
    ])]
    private ?User $user = null;

    //    #[ORM\OneToOne(targetEntity: ProductInfo::class)]
    //    private ?ProductInfo $productInfo = null;

    //    #[ORM\ManyToMany(targetEntity: Test::class)]
    //    private Collection $test;

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    // /**
    //  * @return array
    //  */
    // public function jsonSerialize(): array
    // {
    //     return [
    //         "id" => $this->getId(),
    //         "name" => $this->getName(),
    //         "price" => $this->getPrice(),
    //         "description" => $this->getDescription()
    //         //            "category"    => $this->getCategory()
    //     ];
    // }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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

    #[ORM\PostPersist]
    public function test(){

    }

    //    /**
    //     * @return ProductInfo|null
    //     */
    //    public function getProductInfo(): ?ProductInfo
    //    {
    //        return $this->productInfo;
    //    }
    //
    //    /**
    //     * @param ProductInfo|null $productInfo
    //     */
    //    public function setProductInfo(?ProductInfo $productInfo): void
    //    {
    //        $this->productInfo = $productInfo;
    //    }
    //
    //    /**
    //     * @return Collection
    //     */
    //    public function getTest(): Collection
    //    {
    //        return $this->test;
    //    }
    //
    //    /**
    //     * @param Collection $test
    //     */
    //    public function setTest(Collection $test): void
    //    {
    //        $this->test = $test;
    //    }

}
