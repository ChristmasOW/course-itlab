<?php

namespace App\Entity;

use App\Repository\RecordsRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: RecordsRepository::class)]
class Records implements JsonSerializable
{
    /** @var int|null */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** @var DateTimeInterface|null */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $date = null;

    /** @var string|null */
    #[ORM\Column(length: 255)]
    private ?string $doctor = null;

    /** @var string|null */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $diagnosis = null;

    /** @var string|null */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    /** @var Patients|null */
    #[ORM\ManyToOne(targetEntity: Patients::class, inversedBy: "records")]
    private ?Patients $patients = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param DateTimeInterface|null $date
     * @return $this
     */
    public function setDate(?DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDoctor(): ?string
    {
        return $this->doctor;
    }

    /**
     * @param string|null $doctor
     * @return $this
     */
    public function setDoctor(?string $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
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
     * @return $this
     */
    public function setDiagnosis(?string $diagnosis): self
    {
        $this->diagnosis = $diagnosis;

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
     * @return $this
     */
    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return Patients|null
     */
    public function getPatients(): ?Patients
    {
        return $this->patients;
    }

    /**
     * @param Patients|null $patients
     * @return $this
     */
    public function setPatients(?Patients $patients): self
    {
        $this->patients = $patients;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "patient" => $this->getPatients(),
            "date" => $this->getDate(),
            "doctor" => $this->getDoctor(),
            "diagnosis" => $this->getDiagnosis(),
            "notes" => $this->getNotes()
        ];
    }
}
