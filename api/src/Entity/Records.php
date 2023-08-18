<?php

namespace App\Entity;

use App\Repository\RecordsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecordsRepository::class)]
class Records implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $diagnosis = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\OneToOne(targetEntity: Patients::class)]
    private Patients $patients;

    #[ORM\OneToOne(targetEntity: Doctors::class)]
    private Doctors $doctors;

    #[ORM\OneToOne(targetEntity: Departments::class)]
    private Departments $departments;

    #[ORM\ManyToOne(targetEntity: Medications::class, inversedBy: "records")]
    private Medications $medications;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getDiagnosis(): ?string
    {
        return $this->diagnosis;
    }

    /**
     * @param string|null $diagnosis
     */
    public function setDiagnosis(?string $diagnosis): self
    {
        $this->diagnosis = $diagnosis;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface|null $date
     */
    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * @param string|null $notes
     */
    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return Patients
     */
    public function getPatients(): Patients
    {
        return $this->patients;
    }

    /**
     * @param Patients $patients
     */
    public function setPatients(Patients $patients): self
    {
        $this->patients = $patients;
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

    /**
     * @return Departments
     */
    public function getDepartments(): Departments
    {
        return $this->departments;
    }

    /**
     * @param Departments $departments
     */
    public function setDepartments(Departments $departments): self
    {
        $this->departments = $departments;
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

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "patient" => $this->getPatients(),
            "doctors" => $this->getDoctors(),
            "medication" => $this->getMedications(),
            "department" => $this->getDepartments(),
            "date" => $this->getDate(),
            "diagnosis" => $this->getDiagnosis(),
            "notes" => $this->getNotes()
        ];
    }
}
