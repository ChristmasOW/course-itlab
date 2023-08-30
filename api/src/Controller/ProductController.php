<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/products', name: 'product_create', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        if (!isset(
            $requestData['price'],
            $requestData['name'],
            $requestData['description']
            // $requestData['category']
        )) {
            throw new Exception('Invalid request data');
        }

        // $category = $this->entityManager->getRepository(Category::class)->find($requestData["category"]);
        // if (!$category) {
        //     throw new Exception("Category with id " . $requestData['category'] . " not found");
        // }

        $product = new Product();

        $product
            ->setPrice($requestData['price'])
            ->setName($requestData['name'])
            ->setDescription($requestData['description']);
            // ->setCategory($category);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return new JsonResponse($product, Response::HTTP_CREATED);
    }

    #[Route('/products', name: 'product_get_all', methods: 'GET')]
    public function getAll(): JsonResponse
    {
        /** @var Product $product */
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return new JsonResponse($products);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('product/{id}', name: 'product_get_item', methods: 'GET')]
    public function getItem(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception("Product with id: " . $id . " not found");
        }

        return new JsonResponse($product);
    }

    #[Route('products/{id}', name: 'product_update_item', methods: 'PUT')]
    public function updateProduct(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception("Product with id: " . $id . " not found");
        }

        $product->setName("new name");

        $this->entityManager->flush();

        return new JsonResponse($product);
    }

    #[Route('products/{id}', name: 'product_delete_item', methods: 'DELETE')]
    public function deleteProduct(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception("Product with id: " . $id . " not found");
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return new JsonResponse();
    }
}
