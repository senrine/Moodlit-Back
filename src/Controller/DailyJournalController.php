<?php

namespace App\Controller;

use App\Service\DailyJournalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DailyJournalController extends AbstractController
{
    private DailyJournalService $dailyJournalService;

    public function __construct(DailyJournalService $dailyJournalService)
    {
        $this->dailyJournalService = $dailyJournalService;
    }
    #[Route('/editor/journals/{id}', name: 'app_daily_journal_show', methods: ['GET'])]
    public function showEditorJournals(int $id): JsonResponse
    {
        return $this->json(["journals"=>$this->dailyJournalService->getEditorJournals($id)]);
    }
    #[Route('/createJournal', name: 'app_daily_journal_create', methods: ['POST'])]
    function createJournal(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->json(["journal"=>$this->dailyJournalService->createJournal($data)]);
    }
    #[Route('/deleteJournal/{id}', name: 'app_daily_journal_delete', methods: ['DELETE'])]
    public function deleteJournal(int $id): JsonResponse
    {
        $this->dailyJournalService->deleteJournal($id);
        return $this->json(['success'=>true]);
    }

    #[Route('/getJournal/{id}', name: 'app_daily_journal_get', methods: ['GET'])]
    public function getJournal(int $id): JsonResponse
    {
        return $this->json(["journal"=>$this->dailyJournalService->getJournalById($id)]);
    }

    #[Route('/updateJournal/{id}', name: 'app_daily_journal_update', methods: ['PUT'])]
    public function updateJournal(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->json(['journal'=>$this->dailyJournalService->updateJournal($id, $data)]);
    }

}
