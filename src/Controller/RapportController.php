<?php

namespace App\Controller;

use App\Service\RapportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RapportController extends AbstractController
{
    private RapportService $rapportService;
    public function __construct(RapportService $rapportService)
    {
        $this->rapportService = $rapportService;
    }
    #[Route('/getRapports', name: 'api_rapports', methods: ['GET'])]
    public function getRapports() : JsonResponse
    {
        return $this->json($this->rapportService->getRapports());
    }

    /**
     * @throws \Exception
     */
    #[Route('/createRapport', name: 'api_rapports_create', methods: ['POST'])]
    public function createRapport(Request $request) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->json($this->rapportService->createRapport($data));
    }

    #[Route('deleteRapport/{id}', name: 'api_rapports_delete', methods: ['DELETE'])]
    public function deleteRapport(int $id) : JsonResponse
    {
        $this->rapportService->deleteRapport($id);
        return $this->json(['message' => 'Rapport deleted']);
    }

    #[Route('/getRapport/{id}', name: 'api_rapports_get', methods: ['GET'])]
    public function getRapport(int $id) : JsonResponse
    {
        return $this->json($this->rapportService->getRapport($id));
    }
}
