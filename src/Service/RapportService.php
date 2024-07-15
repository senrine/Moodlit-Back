<?php

namespace App\Service;

use App\Entity\Rapport;
use App\Repository\DailyJournalRepository;
use App\Repository\RapportRepository;
use App\Repository\UserRepository;

class RapportService
{
    private RapportRepository $rapportRepository;
    private DailyJournalRepository $dailyJournalRepository;
    private UserRepository $userRepository;
    public function __construct(RapportRepository $rapportRepository, DailyJournalRepository $dailyJournalRepository, UserRepository $userRepository)
    {
        $this->rapportRepository = $rapportRepository;
        $this->dailyJournalRepository = $dailyJournalRepository;
        $this->userRepository = $userRepository;
    }
    public function getRapports() : array
    {
        $rapports = $this->rapportRepository->findAll();
        $serialized_rapports = [];
        foreach ($rapports as $rapport) {
            $serialized_rapports[] = $rapport->serialize();
        }
        return $serialized_rapports;
    }

    /**
     * @throws \Exception
     */
    public function createRapport(array $data) : array
    {
        $rapport = new Rapport();
        $user = $this->userRepository->find($data['editor_id']);
        if(!$user) {
            throw new \InvalidArgumentException('User not found');
        }
        $rapport->setEditor($user);

        $rapport->setCreatedAt(new \DateTime());

        $journals = $this->dailyJournalRepository->getWeekJournal(new \DateTime(), $data['editor_id']);


        if($this->getMostUsedWord($journals)) {
            $rapport->setMostUsedWord($this->getMostUsedWord($journals));
        }
        $rapport->setAverageSentiment($this->getAverageSentiment($journals) ?? 0);
        $rapport->setHappiestDay($this->getHappiestDay($journals));

        $this->rapportRepository->save($rapport);
        return $rapport->serialize();
    }

    private function getMostUsedWord(array $journals) : string|null
    {
        $words = [];
        foreach ($journals as $journal) {
            $words = array_merge($words, explode(' ', $journal->getContent()));
        }
        $word_count = array_count_values($words);
        arsort($word_count);
        return key($word_count);
    }

    private function getAverageSentiment(array $journals) : int
    {
        $sentiments = [];
        foreach ($journals as $journal) {
            $sentiments[] = $journal->getSentimentScore();
        }
        return array_sum($sentiments) / count($sentiments);
    }

    private function getHappiestDay(array $journals) : \DateTime
    {
        $highest_sentiment = 0;
        $happiest_day = new \DateTime();
        foreach ($journals as $journal) {
            if($journal->getSentimentScore() > $highest_sentiment) {
                $highest_sentiment = $journal->getSentimentScore();
                $happiest_day = $journal->getCreatedAt();
            }
        }
        return $happiest_day;
    }

    public function deleteRapport(int $id) : void
    {
        $rapport = $this->rapportRepository->find($id);
        if(!$rapport) {
            throw new \InvalidArgumentException('Rapport not found');
        }
        $this->rapportRepository->remove($rapport);
    }

    public function getRapport(int $id) : array
    {
        $rapport = $this->rapportRepository->find($id);
        if(!$rapport) {
            throw new \InvalidArgumentException('Rapport not found');
        }
        return $rapport->serialize();
    }
}