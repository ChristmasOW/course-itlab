<?php

namespace App\Controller;


use App\Entity\Patients;
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/patient-create', name: 'patient_create')]
    public function createPatient(Request $request): JsonResponse
    {
        /** @var $requestData */
        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['name'],
            $requestData['age'],
            $requestData['gender'],
            $requestData['phone'],
            $requestData['address']
        )) {
            throw new Exception('Invalid request data');
        }

        /** @var $patient */
        $patient = new Patients();

        $patient
            ->setName($requestData['name'])
            ->setAge($requestData['age'])
            ->setGender($requestData['gender'])
            ->setPhone($requestData['phone'])
            ->setAddress($requestData['address']);

        $this->entityManager->persist($patient);
        $this->entityManager->flush();

        return new JsonResponse($patient, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('/patients', name: 'patients_get_all')]
    public function getAllPatients(): JsonResponse
    {
        /** @var Patients $patients */
        $patients = $this->entityManager->getRepository(Patients::class)->findAll();

        return new JsonResponse($patients, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('patient/{id}', name: 'patient_get_one')]
    public function getPatient(string $id): JsonResponse
    {
        /** @var Patients $patient */
        $patient = $this->entityManager->getRepository(Patients::class)->find($id);

        if (!$patient) {
            throw new Exception("Patient with id: " . $id . " not found");
        }

        return new JsonResponse($patient, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    #[Route('patient-update/{id}', name: 'patient_update_item')]
    public function updatePatient(Request $request, string $id): JsonResponse
    {
        /** @var Patients $patient */
        $patient = $this->entityManager->getRepository(Patients::class)->find($id);

        if (!$patient) {
            throw new Exception("Patient with id: " . $id . " not found");
        }

        /** @var $requestData */
        $requestData = json_decode($request->getContent(), true);

        /** @var $fieldsToUpdate */
        $fieldsToUpdate = ['name', 'age', 'gender', 'phone', 'address'];

        foreach ($fieldsToUpdate as $field) {
            if (isset($requestData[$field])) {
                $setterMethod = 'set' . ucfirst($field);
                $patient->$setterMethod($requestData[$field]);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse($patient, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('patient-delete/{id}', name: 'patient_delete_item')]
    public function deletePatient(string $id): JsonResponse
    {
        /** @var Patients $patient */
        $patient = $this->entityManager->getRepository(Patients::class)->find($id);

        if (!$patient) {
            throw new Exception("Patients with id: " . $id . " not found");
        }

        $this->entityManager->remove($patient);
        $this->entityManager->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: "patients-find", name: "app_find")]
    public function findPatient(Request $request): JsonResponse
    {
        $requestData = $request->query->all();

        /** @var Patients $patient */
        $patient = $this->entityManager->getRepository(Patients::class)->getAllPatients(
            $requestData['itemsPerPage'] ?? 10,
            $requestData['page'] ?? 1,
            $requestData['id'] ?? null,
            $requestData['name'] ?? null,
            $requestData['age'] ?? null,
            $requestData['gender'] ?? null,
            $requestData['phone'] ?? null,
            $requestData['address'] ?? null
        );

        return new JsonResponse($patient, Response::HTTP_OK);
    }
}
