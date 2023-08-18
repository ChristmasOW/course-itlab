<?php

namespace App\Entity;

use App\Repository\DepartmentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartmentsRepository::class)]
class Departments implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\OneToOne(mappedBy: "departments",targetEntity: Records::class)]
    private ?Records $records = null;

    #[ORM\OneToOne(mappedBy: "departments", targetEntity: Doctors::class)]
    private Doctors $doctors;

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
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     */
    public function setLocation(?string $location): self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return Doctors
     */
    public function getDoctors(): Doctors
    {
        return $this->doctors;
    }

    /**
     * @param Doctors $doctors
     */
    public function setDoctors(Doctors $doctors): self
    {
        $this->doctors = $doctors;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "location" => $this->getLocation(),
            "doctor" => $this->getDoctors()
        ];
    }
}
