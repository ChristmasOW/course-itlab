<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use App\Validator\Constraints\CategoryConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[CategoryConstraint]
#[ApiResource(
    collectionOperations: [
        "post" => [
            "method" => "POST",
            "security" => "is_granted('" . User::ROLE_ADMIN . "')"
        ],
        "get" => [
            "method" => "GET",
            "security" => "is_granted('" . User::ROLE_USER . "')"
        ]
    ],
    itemOperations: [
        "get" => [
            "method" => "GET"
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
class Category implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Unique]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Your category name must be at least {{ limit }} characters long',
        maxMessage: 'Your category name cannot be longer that {{ limit }} characters',
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Your type must be at least {{ limit }} characters long',
        maxMessage: 'Your type cannot be longer that {{ limit }} characters',
    )]
    private ?string $type = null;

//    #[ORM\OneToMany(mappedBy: "category", targetEntity: Product::class)]
//    private Collection $products;

//    /**
//     * Category constructor
//     */
//    public function __construct()
//    {
//        $this->products = new ArrayCollection();
//    }

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
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return $this
     */
    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }
//
//    /**
//     * @return ArrayCollection|Collection
//     */
//    public function getProducts(): ArrayCollection|Collection
//    {
//        return $this->products;
//    }
//
//    /**
//     * @param ArrayCollection|Collection $products
//     */
//    public function setProducts(ArrayCollection|Collection $products): self
//    {
//        $this->products = $products;
//        return $this;
//    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => $this->getType()
        ];
    }


}
