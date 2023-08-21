<?php

namespace App\Repository;

use App\Entity\Records;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Records>
 *
 * @method Records|null find($id, $lockMode = null, $lockVersion = null)
 * @method Records|null findOneBy(array $criteria, array $orderBy = null)
 * @method Records[]    findAll()
 * @method Records[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecordsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Records::class);
    }

    /**
     * @param int $itemsPerPage
     * @param int $page
     * @param int|null $id
     * @param string|null $patient
     * @param DateTimeInterface|null $date
     * @param string|null $doctor
     * @param string|null $diagnosis
     * @param string|null $notes
     * @return array
     */
    public function getAllRecords(int     $itemsPerPage, int $page, ?int $id = null, ?int $patientId = null,
                                  ?string $patientAge = null, ?string $patientGender = null, ?string $patientPhone = null,
                                  ?string $patientAddress = null, ?string $patient = null, ?DateTimeInterface $date = null,
                                  ?string $doctor = null, ?string $diagnosis = null, ?string $notes = null): array
    {
        return $this->createQueryBuilder('records')
            ->join('records.patients', 'patients')
            ->andWhere("records.id LIKE :id")
            ->andWhere("patients.id LIKE :patientId")
            ->andWhere("patients.name LIKE :patient")
            ->andWhere("patients.age LIKE :patientAge")
            ->andWhere("patients.gender LIKE :patientGender")
            ->andWhere("patients.phone LIKE :patientPhone")
            ->andWhere("patients.address LIKE :patientAddress")
            ->andWhere("records.date LIKE :date")
            ->andWhere("records.doctor LIKE :doctor")
            ->andWhere("records.diagnosis LIKE :diagnosis")
            ->andWhere("records.notes LIKE :notes")
            ->setParameter("id", "%" . $id . "%")
            ->setParameter("patientId", "%" . $patientId . "%")
            ->setParameter('patientAge', "%" . $patientAge . "%")
            ->setParameter('patientGender', "%" . $patientGender . "%")
            ->setParameter('patientPhone', "%" . $patientPhone . "%")
            ->setParameter('patientAddress', "%" . $patientAddress . "%")
            ->setParameter("patient", "%" . $patient . "%")
            ->setParameter("date", "%" . $date . "%")
            ->setParameter("doctor", "%" . $doctor . "%")
            ->setParameter("diagnosis", "%" . $diagnosis . "%")
            ->setParameter("notes", "%" . $notes . "%")
            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)
            ->orderBy('records.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
