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

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('/category-create', name: 'category_create')]
    public function create(Request $request): JsonResponse
    {
        /** @var $requestData */
        $requestData = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->getUser();

        if (!isset(
            $requestData['name'],
            $requestData['type']
        )) {
            throw new Exception('Invalid request data');
        }

        /** @var $category */
        $category = new Category();

        $category
            ->setName($requestData['name'])
            ->setType($requestData['type']);

        if (in_array(User::ROLE_ADMIN, $user->getRoles())) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return new JsonResponse($category, Response::HTTP_CREATED);
        } else {
            return new JsonResponse("You are not Admin", Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * @return JsonResponse
     */
    #[Route('/categories', name: 'category_get_all')]
    public function getAll(): JsonResponse
    {
        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->findAll();

        return new JsonResponse($category, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('category/{id}', name: 'category_get_item')]
    public function getItem(string $id): JsonResponse
    {
        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw new Exception("Category with id: " . $id . " not found");
        }

        return new JsonResponse($category, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    #[Route('category-update/{id}', name: 'category_update_item')]
    public function updateCategory(Request $request, string $id): JsonResponse
    {
        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        /** @var User $user */
        $user = $this->getUser();

        if (!$category) {
            throw new Exception("Category with id: " . $id . " not found");
        }

        /** @var $requestData */
        $requestData = json_decode($request->getContent(), true);

        /** @var $fieldsToUpdate */
        $fieldsToUpdate = ['name', 'type'];

        foreach ($fieldsToUpdate as $field) {
            if (isset($requestData[$field])) {
                $setterMethod = 'set' . ucfirst($field);
                $category->$setterMethod($requestData[$field]);
            }
        }

        if (in_array(User::ROLE_ADMIN, $user->getRoles())) {
            $this->entityManager->flush();

            return new JsonResponse($category, Response::HTTP_OK);
        } else {
            return new JsonResponse("You are not Admin", Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('category-delete/{id}', name: 'category_delete_item')]
    public function deleteCategory(string $id): JsonResponse
    {
        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        /** @var User $user */
        $user = $this->getUser();

        if (!$category) {
            throw new Exception("Category with id: " . $id . " not found");
        }

        if (in_array(User::ROLE_ADMIN, $user->getRoles())) {
            $this->entityManager->remove($category);
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
    #[Route(path: "category-find", name: "app_find")]
    public function findCategory(Request $request): JsonResponse
    {
        $requestData = $request->query->all();

        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->getAllCategories(
            $requestData['itemsPerPage'] ?? 10,
            $requestData['page'] ?? 1,
            $requestData['id'] ?? null,
            $requestData['name'] ?? null,
            $requestData['type'] ?? null
        );

        return new JsonResponse($category, Response::HTTP_OK);
    }
}
