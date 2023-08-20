<?php

namespace App\Repository;

use App\Entity\Patients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patients>
 *
 * @method Patients|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patients|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patients[]    findAll()
 * @method Patients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patients::class);
    }

    /**
     * @param int $itemsPerPage
     * @param int $page
     * @param int|null $id
     * @param string|null $name
     * @param string|null $age
     * @param string|null $gender
     * @param string|null $phone
     * @param string|null $address
     * @return array
     */
    public function getAllPatients(int     $itemsPerPage, int $page, ?int $id = null, ?string $name = null, ?string $age = null,
                                   ?string $gender = null, ?string $phone = null, ?string $address = null): array
    {
        return $this->createQueryBuilder('patients')
            ->andWhere("patients.id LIKE :id")
            ->andWhere("patients.name LIKE :name")
            ->andWhere("patients.age LIKE :age")
            ->andWhere("patients.gender LIKE :gender")
            ->andWhere("patients.phone LIKE :phone")
            ->andWhere("patients.address LIKE :address")
            ->setParameter("id", "%" . $id . "%")
            ->setParameter("name", "%" . $name . "%")
            ->setParameter("age", "%" . $age . "%")
            ->setParameter("gender", "%" . $gender . "%")
            ->setParameter("phone", "%" . $phone . "%")
            ->setParameter("address", "%" . $address . "%")
            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)
            ->orderBy('patients.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
