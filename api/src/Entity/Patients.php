<?php

namespace App\Entity;

use App\Repository\PatientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: PatientsRepository::class)]
class Patients implements JsonSerializable
{
    /** @var int|null */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** @var string|null */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /** @var int|null */
    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    /** @var string|null */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gender = null;

    /** @var string|null */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    /** @var string|null */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    /** @var Collection */
    #[ORM\OneToMany(mappedBy: "patients", targetEntity: Records::class)]
    private Collection $records;

    /**
     * Records constructor
     */
    public function __construct()
    {
        $this->records = new ArrayCollection();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAge(): ?int
    {
        return $this->age;
    }

    /**
     * @param int|null $age
     * @return $this
     */
    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     * @return $this
     */
    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return $this
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return $this
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRecords(): Collection
    {
        return $this->records;
    }

    /**
     * @param Collection $records
     * @return $this
     */
    public function setRecords(Collection $records): self
    {
        $this->records = $records;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id"   => $this->getId(),
            "name" => $this->getName(),
            "age" => $this->getAge(),
            "gender" => $this->getGender(),
            "phone" => $this->getPhone(),
            "address" => $this->getAddress()
        ];
    }
}
