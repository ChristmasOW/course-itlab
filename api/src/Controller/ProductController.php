<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
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

    /**
     * @param Request $request
     * @return JsonResponse
     * @throw Exception
     */
    #[Route('/product-create', name: 'product_create')]
    public function create(Request $request): JsonResponse
    {
        /** @var $requestData */
        $requestData = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->getUser();

        if (!isset(
            $requestData['price'],
            $requestData['name'],
            $requestData['description']
        )) {
            throw new Exception('Invalid request data');
        }

        /** @var $product */
        $product = new Product();

        $product
            ->setPrice($requestData['price'])
            ->setName($requestData['name'])
            ->setDescription($requestData['description']);

        if (in_array(User::ROLE_ADMIN, $user->getRoles())) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return new JsonResponse($product, Response::HTTP_CREATED);
        } else {
            return new JsonResponse("You are not Admin", Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * @return JsonResponse
     */
    #[Route('/products', name: 'product_get_all')]
    public function getAll(): JsonResponse
    {
        /** @var Product $product */
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return new JsonResponse($products, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throw Exception
     */
    #[Route('products/{id}', name: 'product_get_item')]
    public function getItem(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception("Product with id: " . $id . " not found");
        }

        return new JsonResponse($product, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    #[Route('product-update/{id}', name: 'product_update_item')]
    public function updateProduct(Request $request ,string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        /** @var User $user */
        $user = $this->getUser();

        if (!$product) {
            throw new Exception("Product with id: " . $id . " not found");
        }

        /** @var $requestData */
        $requestData = json_decode($request->getContent(), true);

        /** @var $fieldsToUpdate */
        $fieldsToUpdate = ['name', 'price', 'description'];

        foreach ($fieldsToUpdate as $field) {
            if (isset($requestData[$field])) {
                $setterMethod = 'set' . ucfirst($field);
                $product->$setterMethod($requestData[$field]);
            }
        }

        if (in_array(User::ROLE_ADMIN, $user->getRoles())) {
            $this->entityManager->flush();

            return new JsonResponse($product, Response::HTTP_OK);
        } else {
            return new JsonResponse("You are not Admin", Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('product-delete/{id}', name: 'product_delete_item')]
    public function deleteProduct(string $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception("Product with id: " . $id . " not found");
        }

        if (in_array(User::ROLE_ADMIN, $user->getRoles())) {
            $this->entityManager->remove($product);
            $this->entityManager->flush();

            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        } else {
            return new JsonResponse("You are not Admin", Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: "product-find", name: "app_find_product")]
    public function findProduct(Request $request): JsonResponse
    {
        $requestData = $request->query->all();

        /** @var Product $product */
        $product = $this->entityManager->getRepository(Category::class)->getAllProducts(
            $requestData['itemsPerPage'] ?? 10,
            $requestData['page'] ?? 1,
            $requestData['id'] ?? null,
            $requestData['categoryName'] ?? null,
            $requestData['categoryType'] ?? null,
            $requestData['name'] ?? null,
            $requestData['price'] ?? null,
            $requestData['description'] ?? null
        );

        return new JsonResponse($product, Response::HTTP_OK);
    }
}
