<?php

namespace App\Entity;

use App\Repository\PatientsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: PatientsRepository::class)]

class Patients implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\ManyToOne(targetEntity: Doctors::class, inversedBy: "patients")]
    private ?Doctors $doctors = null;

    #[ORM\ManyToOne(targetEntity: Medications::class, inversedBy: "patients")]
    private Medications $medications;

    #[ORM\OneToOne(mappedBy: "patients",targetEntity: Records::class)]
    private ?Records $records = null;

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
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
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
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return Doctors|null
     */
    public function getDoctors(): ?Doctors
    {
        return $this->doctors;
    }

    /**
     * @param Doctors|null $doctors
     */
    public function setDoctors(?Doctors $doctors): self
    {
        $this->doctors = $doctors;
        return $this;
    }

    /**
     * @return Medications
     */
    public function getMedications(): Medications
    {
        return $this->medications;
    }

    /**
     * @param Medications $medications
     */
    public function setMedications(Medications $medications): self
    {
        $this->medications = $medications;
        return $this;
    }

    /**
     * @return Records|null
     */
    public function getRecords(): ?Records
    {
        return $this->records;
    }

    /**
     * @param Records|null $records
     */
    public function setRecords(?Records $records): self
    {
        $this->records = $records;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "gender" => $this->getGender(),
            "phone" => $this->getPhone(),
            "medications" => $this->getMedications(),
            "doctors" => $this->getDoctors(),
            "record" => $this->getRecords()
        ];
    }
}
