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

class CategoryController extends AbstractController
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

    // /**
    //  * @param Request $request
    //  * @return JsonResponse
    //  * @throws Exception
    //  */
    // #[Route('/categories', name: 'category_create', methods: 'POST')]
    // public function create(Request $request): JsonResponse
    // {
    //     $requestData = json_decode($request->getContent(), true);
    //     if (!isset($requestData['name'], $requestData['type'])) {
    //         throw new Exception('Invalid request data');
    //     }
    //     $category = new Category();

    //     $category->setName($requestData['name']);
    //     $category->setType($requestData['type']);

    //     $this->entityManager->persist($category);
    //     $this->entityManager->flush();

    //     return new JsonResponse($category, Response::HTTP_CREATED);
    // }

    // /**
    //  * @return JsonResponse
    //  */
    // #[Route('/categories', name: 'product_get_all', methods: 'GET')]
    // public function getAll(): JsonResponse
    // {
    //     /** @var Category $category */
    //     $category = $this->entityManager->getRepository(Category::class)->findAll();
    //     return new JsonResponse($category);
    // }

    // /**
    //  * @param string $id
    //  * @return JsonResponse
    //  * @throws Exception
    //  */
    // #[Route('categories/{id}', name: 'product_get_item', methods: 'GET')]
    // public function getItem(string $id): JsonResponse
    // {
    //     /** @var Category $category */
    //     $category = $this->entityManager->getRepository(Category::class)->find($id);

    //     if (!$category) {
    //         throw new Exception("Category with id: " . $id . " not found");
    //     }

    //     return new JsonResponse($category);
    // }

    // /**
    //  * @param string $id
    //  * @return JsonResponse
    //  * @throws Exception
    //  */
    // #[Route('categories/{id}', name: 'product_update_item', methods: 'PUT')]
    // public function updateProduct(string $id): JsonResponse
    // {
    //     /** @var Category $category */
    //     $category = $this->entityManager->getRepository(Category::class)->find($id);
        
    //     if (!$category) {
    //         throw new Exception("Category with id: " . $id . " not found");
    //     }
        
    //     $category->setName("new name");
        
    //     $this->entityManager->flush();
        
    //     return new JsonResponse($category);
    // }

    // /**
    //  * @param string $id
    //  * @return JsonResponse
    //  * @throws Exception
    //  */
    // #[Route('categories/{id}', name: 'product_delete_item', methods: 'DELETE')]
    // public function deleteProduct(string $id): JsonResponse
    // {
    //     /** @var Category $category */
    //     $category = $this->entityManager->getRepository(Category::class)->find($id);
        
    //     if (!$category) {
    //         throw new Exception("Category with id: " . $id . " not found");
    //     }
                
    //     $this->entityManager->remove($category);
    //     $this->entityManager->flush();
        
    //     return new JsonResponse();
    // }
}
