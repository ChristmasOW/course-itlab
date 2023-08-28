<?php

namespace App\Controller;

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
            $requestData['products'],
            $requestData['quantity'],
        )) {
            throw new Exception('Invalid request data');
        }

        /** @var Product $product */
        $products = $this->entityManager->getRepository(Product::class)->find($requestData["products"]);

        $quantity = $requestData['quantity'];
        $amount = $products->getPrice() * $quantity;

        /** @var $purchase */
        $purchase = new Purchase();

        $purchase
            ->setUser($user)
            ->addProduct($products)
            ->setQuantity($requestData['quantity'], $products)
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
    #[Route('/purchases', name: 'purchase_get_all')]
    public function getAllPurchase(): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $userPurchases = $this->entityManager
            ->getRepository(Purchase::class)
            ->findBy(['user' => $currentUser]);

        $purchaseData = [];
        foreach ($userPurchases as $purchase) {
            $purchaseData[] = [
                'id' => $purchase->getId(),
                'purchaseDate' => $purchase->getPurchaseDate(),
                "product" => $purchase->getProducts()->toArray(),
                'quantity' => $purchase->getQuantity(),
                'amount' => $purchase->getAmount()
            ];
        }

        return new JsonResponse($purchaseData, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('purchases/{id}', name: 'purchase_get_item')]
    public function getItem(string $id): JsonResponse
    {
        /** @var Purchase $purchase */
        $purchase = $this->entityManager->getRepository(Purchase::class)->find($id);

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($purchase->getUser() !== $currentUser) {
            return new JsonResponse("Purchase with id: " . $id . " not found", Response::HTTP_NOT_FOUND);
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
        if ($currentUser !== $purchase->getUser()) {
            return new JsonResponse("Purchase not found", Response::HTTP_NOT_FOUND);
        }

        /** @var $requestData */
        $requestData = json_decode($request->getContent(), true);

        /** @var Product $product */
        $products = $this->entityManager->getRepository(Product::class)->find($requestData["products"]);

        $quantity = $requestData['quantity'];
        $amount = $products->getPrice() * $quantity;
        
        $purchase
            ->addProduct($products)
            ->setQuantity($requestData['quantity'], $products)
            ->setAmount($amount)
            ->setPurchaseDate(new DateTime());

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

        $currentUser = $this->getUser();
        if ($currentUser !== $purchase->getUser()) {
            return new JsonResponse("Purchase not found", Response::HTTP_NOT_FOUND);
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
            $requestData['productId'] ?? null
        );

        return new JsonResponse($purchases, Response::HTTP_OK);
    }
}
