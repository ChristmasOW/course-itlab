<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param int $itemsPerPage
     * @param int $page
     * @param int|null $id
     * @param string|null $categoryName
     * @param string|null $categoryType
     * @param string|null $name
     * @param string|null $price
     * @param string|null $description
     * @return array
     */
    public function getAllProducts(int     $itemsPerPage, int $page, ?int $id = null,
                                   ?string $categoryName = null, ?string $categoryType = null,
                                   ?string $name = null, ?string $price = null, ?string $description = null): array
    {
        return $this->createQueryBuilder('product')
            ->join('product.category', 'category')
            ->andWhere("product.id LIKE :id")
            ->andWhere('category.name LIKE :categoryName')
            ->andWhere('category.type LIKE :categoryType')
            ->andWhere("product.name LIKE :name")
            ->andWhere("product.price LIKE :price")
            ->andWhere("product.description LIKE :description")
            ->setParameter("id", "%" . $id . "%")
            ->setParameter("categoryName", "%" . $categoryName . "%")
            ->setParameter("categoryType", "%" . $categoryType . "%")
            ->setParameter("name", "%" . $name . "%")
            ->setParameter("price", "%" . $price . "%")
            ->setParameter("description", "%" . $description . "%")
            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)
            ->orderBy('product.name', 'ASK')
            ->getQuery()
            ->getResult();
    }
}
