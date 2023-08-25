<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param int $itemsPerPage
     * @param int $page
     * @param int|null $id
     * @param string|null $name
     * @param string|null $type
     * @return array
     */
    public function getAllCategories(int $itemsPerPage, int $page, ?int $id = null, ?string $name = null, ?string $type = null): array
    {
        return $this->createQueryBuilder('patients')
            ->andWhere("category.id LIKE :id")
            ->andWhere("category.name LIKE :name")
            ->andWhere("category.type LIKE :type")
            ->setParameter("id", "%" . $id . "%")
            ->setParameter("name", "%" . $name . "%")
            ->setParameter("type", "%" . $type . "%")
            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)
            ->orderBy('category.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
