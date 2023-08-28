<?php

namespace App\Repository;

use App\Entity\Purchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Purchase>
 *
 * @method Purchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Purchase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Purchase[]    findAll()
 * @method Purchase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchase::class);
    }

    /**
     * @param int $itemsPerPage
     * @param int $page
     * @param int|null $userId
     * @param int|null $productId
     * @return array
     */
    public function getAllPurchases(int $itemsPerPage, int $page, ?int $userId = null, ?int $productId = null): array
    {
        return $this->createQueryBuilder('purchase')
            ->leftJoin('purchase.product', 'product')
            ->leftJoin('product.category', 'productCategory')
            ->andWhere('purchase.user = :userId')
            ->andWhere('purchase.product = :productId')
            ->setParameter('userId', "%" . $userId . "%")
            ->setParameter('productId', "%" . $productId . "%")
            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)
            ->orderBy('purchase.purchaseDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
