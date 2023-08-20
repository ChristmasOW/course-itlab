<?php

namespace App\Repository;

use App\Entity\Records;
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

    public function getAllRecords(int     $itemsPerPage, int $page, ?string $patient = null, ?string $date = null,
                                   ?string $doctor = null, ?string $diagnosis = null, ?string $notes = null): array
    {
        return $this->createQueryBuilder('records')
            ->join('records.patients_id', 'patient')
            ->andWhere("records.patient LIKE :patient")
            ->andWhere("records.date LIKE :date")
            ->andWhere("records.doctor LIKE :doctor")
            ->andWhere("records.diagnosis LIKE :diagnosis")
            ->andWhere("records.notes LIKE :notes")
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
