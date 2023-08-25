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
class PurchaseRepository extends ServiceEntityRepository
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
     * @param int|null $userId
     * @param int|null $productId
     * @param int|null $categoryId
     * @return array
     */
    public function getAllPurchases(int $itemsPerPage, int $page, ?int $userId = null, ?int $productId = null, ?int $categoryId = null): array
    {
        return $this->createQueryBuilder('purchase')
            ->leftJoin('purchase.product', 'product')
            ->leftJoin('product.category', 'productCategory')
            ->andWhere('purchase.user = :userId')
            ->andWhere('purchase.product = :productId')
            ->andWhere('category.id = :categoryId')
            ->setParameter('userId', "%" . $userId . "%")
            ->setParameter('productId', "%" . $productId . "%")
            ->setParameter('categoryId', "%" . $categoryId . "%")
            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)
            ->orderBy('purchase.purchaseDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
