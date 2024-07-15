<?php

namespace App\Service;

use App\Entity\DailyJournal;
use App\Repository\DailyJournalRepository;
use App\Repository\UserRepository;

class DailyJournalService
{
    private DailyJournalRepository $dailyJournalRepository;
    private UserRepository $userRepository;

    public function __construct(DailyJournalRepository $dailyJournalRepository, UserRepository $userRepository)
    {
        $this->dailyJournalRepository = $dailyJournalRepository;
        $this->userRepository = $userRepository;
    }

    public function getEditorJournals(int $id): array
    {
        $user = $this->userRepository->find($id);
        if($user === null){
            return [];
        }
        $journals = $this->dailyJournalRepository->findBy(['editor'=>$user]);
        $serializedJournals = [];
        foreach($journals as $journal){
            $serializedJournals[] = $journal->getTitle();
        }
        return $serializedJournals;
    }

    public function createJournal(array $data) :  array
    {
        $journal = new DailyJournal();
        $journal->setTitle($data['title']);

        $user = $this->userRepository->find($data['editor_id']);
        if($user === null){
            return [];
        }
        $journal->setEditor($user);
        $journal->setContent($data['content']);
        $journal->setSentimentScore($data['sentiment_score']);
        $journal->setCreatedAt(new \DateTime());
        $this->dailyJournalRepository->save($journal);
        return $journal->serialize();
    }

    public function deleteJournal(int $id): void
    {
        $journal = $this->dailyJournalRepository->find($id);
        $this->dailyJournalRepository->remove($journal);
    }

    public function getJournalById(int $id): array
    {
        $journal = $this->dailyJournalRepository->find($id);
        return $journal->serialize();
    }

    public function updateJournal(int $id, array $data): array
    {
        $journal = $this->dailyJournalRepository->find($id);
        if(array_key_exists('title', $data)){
            $journal->setTitle($data['title']);
        }
        if(array_key_exists('content', $data)){
            $journal->setContent($data['content']);
        }
        $this->dailyJournalRepository->save($journal);

        return $journal->serialize();
    }
}