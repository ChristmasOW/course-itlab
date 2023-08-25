<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
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
    #[Route('/purchase-create', name: 'purchase_create')]
    public function create(Request $request): JsonResponse
    {
        /** @var $requestData */
        $requestData = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->getUser();

        if (!isset(
            $requestData['user'],
            $requestData['category'],
            $requestData['quantity'],
        )) {
            throw new Exception('Invalid request data');
        }

        $user = $this->entityManager->getRepository(User::class)->find($requestData['user']);
        $product = $this->entityManager->getRepository(Product::class)->find($requestData["product"]);
        $category = $this->entityManager->getRepository(Category::class)->find($requestData["category"]);

        $quantity = $requestData['quantity'];
        $amount = $product->getPrice() * $quantity;

        /** @var $purchase */
        $purchase = new Purchase();

        $purchase
            ->setUser($user)
            ->setProduct($product)
            ->setCategory($category)
            ->setQuantity($requestData['quantity'])
            ->setAmount($amount)
            ->setPurchaseDate(new DateTime());

        if (in_array(User::ROLE_USER, $user->getRoles())) {
            $this->entityManager->persist($purchase);
            $this->entityManager->flush();

            return new JsonResponse($purchase, Response::HTTP_CREATED);
        } else {
            return new JsonResponse("You are not User", Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * @return JsonResponse
     */
    #[Route('purchases', name: 'purchase_get_all')]
    public function getAllPurchases(): JsonResponse
    {
        /** @var Purchase $purchases */
        $purchases = $this->entityManager->getRepository(Purchase::class)->findAll();

        return new JsonResponse($purchases, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('purchase/{id}', name: 'purchase_get_item')]
    public function getItem(string $id): JsonResponse
    {
        /** @var Purchase $purchase */
        $purchase = $this->entityManager->getRepository(Purchase::class)->find($id);

        if (!$purchase) {
            throw new Exception("Purchase with id: " . $id . " not found");
        }

        return new JsonResponse($purchase, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('purchase-update/{id}', name: 'purchase_update_item')]
    public function updatePurchase(Request $request, string $id): JsonResponse
    {
        /** @var Purchase $purchase */
        $purchase = $this->entityManager->getRepository(Purchase::class)->find($id);

        if (!$purchase) {
            throw new Exception("Purchase with id: " . $id . " not found");
        }

        $currentUser = $this->getUser();
        if ($currentUser !== $purchase->getUser() || !in_array(User::ROLE_ADMIN, $currentUser->getRoles())) {
            return new JsonResponse("You are not allowed to edit this purchase", Response::HTTP_FORBIDDEN);
        }

        /** @var $requestData */
        $requestData = json_decode($request->getContent(), true);

        $category = $this->entityManager->getRepository(Category::class)->find($requestData["category"]);

        /** @var $fieldsToUpdate */
        $fieldsToUpdate = ['name', 'price', 'description'];

        foreach ($fieldsToUpdate as $field) {
            if (isset($requestData[$field])) {
                $setterMethod = 'set' . ucfirst($field);
                $purchase->$setterMethod($requestData[$field]);
            }
        }

        $purchase
            ->setCategory($category);

        $this->entityManager->flush();

        return new JsonResponse($purchase, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('purchase-delete/{id}', name: 'purchase_delete_item')]
    public function deletePurchase(string $id): JsonResponse
    {
        /** @var Purchase $purchase */
        $purchase = $this->entityManager->getRepository(Purchase::class)->find($id);

        if (!$purchase) {
            throw new Exception("Purchase with id: " . $id . " not found");
        }

        $currentUser = $this->getUser();
        if ($currentUser !== $purchase->getUser() || !in_array(User::ROLE_ADMIN, $currentUser->getRoles())) {
            return new JsonResponse("You are not allowed to delete this purchase", Response::HTTP_FORBIDDEN);
        }

        $this->entityManager->remove($purchase);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Purchase deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: "purchase-find", name: "app_find_purchase")]
    public function findPurchase(Request $request): JsonResponse
    {
        $requestData = $request->query->all();

        /** @var Purchase $purchases */
        $purchases = $this->entityManager->getRepository(Purchase::class)->getAllPurchases(
            $requestData['itemsPerPage'] ?? 10,
            $requestData['page'] ?? 1,
            $requestData['userId'] ?? null,
            $requestData['productId'] ?? null,
            $requestData['categoryId'] ?? null
        );

        return new JsonResponse($purchases, Response::HTTP_OK);
    }
}
