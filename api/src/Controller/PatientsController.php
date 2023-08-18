<?php

namespace App\Controller;

use App\Entity\Doctors;
use App\Entity\Medications;
use App\Entity\Patients;
use App\Entity\Records;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PatientsController extends AbstractController
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

    #[Route('/patient-create', name: 'patient_create')]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        if (!isset(
            $requestData['name'],
            $requestData['gender'],
            $requestData['phone'],
            $requestData['medications'],
            $requestData['doctors'],
            $requestData['record']
        )) {
            throw new Exception('Invalid request data');
        }

        $medications = $this->entityManager->getRepository(Medications::class)->find($requestData["medications"]);
        if (!$medications) {
            throw new Exception("Medications with id " . $requestData['medications'] . " not found");
        }
        $doctors = $this->entityManager->getRepository(Doctors::class)->find($requestData["doctors"]);
        if (!$doctors) {
            throw new Exception("Doctor with id " . $requestData['doctors'] . " not found");
        }
        $record = $this->entityManager->getRepository(Records::class)->find($requestData["record"]);
        if (!$record) {
            throw new Exception("Record with id " . $requestData['record'] . " not found");
        }
        $patient = new Patients();

        $patient
            ->setName($requestData['name'])
            ->setGender($requestData['gender'])
            ->setPhone($requestData['phone'])
            ->setMedications($medications)
            ->setDoctors($doctors)
            ->setRecords($record);

        $this->entityManager->persist($patient);
        $this->entityManager->flush();

        return new JsonResponse($patient, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: "patients-test", name: "app_test")]
    public function test(Request $request): JsonResponse
    {
        $patient = $this->entityManager->getRepository(Patients::class)->getAllPatients('AAA');

        return new JsonResponse($patient);
    }

    #[Route('/products', name: 'product_get_all')]
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
    #[Route('product/{id}', name: 'product_get_item')]
    public function getItem(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception("Product with id: " . $id . " not found");
        }

        return new JsonResponse($product);
    }

    #[Route('product-update/{id}', name: 'product_update_item')]
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

    #[Route('product-delete/{id}', name: 'product_delete_item')]
    public function deleteProduct(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception("Product with id: " . $id . " not found");
        }

        $product->setName("new name");

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return new JsonResponse();
    }
}
