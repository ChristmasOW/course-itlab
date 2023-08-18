<?php

namespace App\Entity;

use App\Repository\DoctorsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctorsRepository::class)]
class Doctors implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $specialization = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 2, scale: '0')]
    private ?string $salary = null;

    #[ORM\OneToMany(mappedBy: "doctors", targetEntity: Patients::class)]
    private Collection $patients;

    #[ORM\OneToOne(mappedBy: "doctors",targetEntity: Records::class)]
    private ?Records $records = null;

    #[ORM\OneToOne(targetEntity: Departments::class)]
    private ?Departments $departments = null;

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
    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    /**
     * @param string|null $specialization
     */
    public function setSpecialization(?string $specialization): self
    {
        $this->specialization = $specialization;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalary(): ?string
    {
        return $this->salary;
    }

    /**
     * @param string|null $salary
     */
    public function setSalary(?string $salary): self
    {
        $this->salary = $salary;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    /**
     * @param Collection $patients
     */
    public function setPatients(Collection $patients): self
    {
        $this->patients = $patients;
        return $this;
    }

    /**
     * @return Departments|null
     */
    public function getDepartments(): ?Departments
    {
        return $this->departments;
    }

    /**
     * @param Departments|null $departments
     */
    public function setDepartments(?Departments $departments): self
    {
        $this->departments = $departments;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "specialization" => $this->getSpecialization(),
            "salary" => $this->getSalary(),
            "department" => $this->getDepartments(),
            "patient" => $this->getPatients(),
        ];
    }
}
