<?php

namespace App\Controller;

use App\Entity\Patients;
use App\Entity\Records;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecordsController extends AbstractController
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
     * @throws \Exception
     */
    #[Route('/record-create', name: 'record_create')]
    public function createRecord(Request $request): JsonResponse
    {
        /** @var $requestData */
        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['patient'],
            $requestData['date'],
            $requestData['doctor'],
            $requestData['diagnosis'],
            $requestData['notes']
        )) {
            throw new Exception('Invalid request data');
        }

        $patient = $this->entityManager->getRepository(Patients::class)->find($requestData["patient"]);

        if (!$patient) {
            throw new Exception("Patient with id " . $requestData['patient'] . " not found");
        }

        $date = new DateTime($requestData['date']);

        /** @var $record */
        $record = new Records();

        $record
            ->setPatients($patient)
            ->setDate($date)
            ->setDoctor($requestData['doctor'])
            ->setDiagnosis($requestData['diagnosis'])
            ->setNotes($requestData['notes']);

        $this->entityManager->persist($record);
        $this->entityManager->flush();

        return new JsonResponse($record, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('/records', name: 'patients_get_all')]
    public function getAllRecords(): JsonResponse
    {
        /** @var Records $record */
        $record = $this->entityManager->getRepository(Records::class)->findAll();

        return new JsonResponse($record, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('record/{id}', name: 'record_get_one')]
    public function getRecord(string $id): JsonResponse
    {
        /** @var Records $record */
        $record = $this->entityManager->getRepository(Records::class)->find($id);

        if (!$record) {
            throw new Exception("Record with id: " . $id . " not found");
        }

        return new JsonResponse($record, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    #[Route('record-update/{id}', name: 'record_update_item')]
    public function updateRecord(Request $request, string $id): JsonResponse
    {
        /** @var Patients $record */
        $record = $this->entityManager->getRepository(Records::class)->find($id);

        if (!$record) {
            throw new Exception("Record with id: " . $id . " not found");
        }

        /** @var $requestData */
        $requestData = json_decode($request->getContent(), true);

        /** @var $fieldsToUpdate */
        $fieldsToUpdate = ['patients_id', 'date', 'doctor', 'diagnosis', 'notes'];

        foreach ($fieldsToUpdate as $field) {
            if (isset($requestData[$field])) {
                $setterMethod = 'set' . ucfirst($field);
                $record->$setterMethod($requestData[$field]);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse($record, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('record-delete/{id}', name: 'record_delete_item')]
    public function deleteRecord(string $id): JsonResponse
    {
        /** @var Patients $record */
        $record = $this->entityManager->getRepository(Records::class)->find($id);

        if (!$record) {
            throw new Exception("Records with id: " . $id . " not found");
        }

        $this->entityManager->remove($record);
        $this->entityManager->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: "records-find", name: "app_find_records")]
    public function findRecord(Request $request): JsonResponse
    {
        $requestData = $request->query->all();

        /** @var Records $record */
        $record = $this->entityManager->getRepository(Records::class)->getAllRecords(
            $requestData['itemsPerPage'] ?? 10,
            $requestData['page'] ?? 1,
            $requestData['id'] ?? null,
            $requestData['patient'] ?? null,
            $requestData['date'] ?? null,
            $requestData['doctor'] ?? null,
            $requestData['diagnosis'] ?? null,
            $requestData['notes'] ?? null
        );

        return new JsonResponse($record, Response::HTTP_OK);
    }
}
