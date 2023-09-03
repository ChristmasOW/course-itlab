<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TestController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var DenormalizerInterface
     */
    private DenormalizerInterface $denormalizer;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param DenormalizerInterface $denormalizer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        DenormalizerInterface $denormalizer,
        ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    #[Route(path: "test", name: "app_test")]
    public function test(Request $request): JsonResponse
    {
        // $requestData = json_decode($request->getContent(), true);

        // $product = $this->denormalizer->denormalize($requestData, Product::class, "array");

        // $errors = $this->validator->validate($product);

        // if (count($errors) > 0) {
        //     return new JsonResponse((string)$errors);
        // }

        return new JsonResponse("test");
    }

    /**
     * @param array $products
     * @return array
     */
    public function fetchProductsForUser(array $products): array
    {
        $fetchedProductsForUser = null;

        /** @var Product $product */
        foreach ($products as $product) {
            $tmpProductData = $product->jsonSerialize();

            unset($tmpProductData['description']);
            $fetchedProductsForUser[] = $tmpProductData;
        }

        return $fetchedProductsForUser;
    }

}