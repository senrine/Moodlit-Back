<?php

namespace App\Service;

use App\Entity\DailyJournal;
use App\Repository\DailyJournalRepository;
use App\Repository\UserRepository;
use DateTime;

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
        if ($user === null) {
            return [];
        }
        $journals = $this->dailyJournalRepository->findBy(['editor' => $user]);
        $serializedJournals = [];
        foreach ($journals as $journal) {
            $serializedJournals[] = $journal->getTitle();
        }
        return $serializedJournals;
    }


    /**
     * @throws \Exception
     */
    public function createJournal(array $data): array
    {
        $journal = new DailyJournal();
        $journal->setTitle($data['title']);

        $user = $this->userRepository->find($data['editor_id']);
        if ($user === null) {
            throw new \InvalidArgumentException('User not found');
        }
        $journal->setEditor($user);
        $journal->setContent($data['content']);
        $journal->setSentimentScore($data['sentiment_score']);
        $journal->setCreatedAt($this->dateFormat($data['created_at']));

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
        if (!$journal) {
            throw new \InvalidArgumentException('Journal not found');
        }
        return $journal->serialize();
    }

    public function updateJournal(int $id, array $data): array
    {
        $journal = $this->dailyJournalRepository->find($id);
        if (!$journal) {
            throw new \InvalidArgumentException('Journal not found');
        }
        if (array_key_exists('title', $data)) {
            if ($journal->getTitle() !== $data['title']) {
                $journal->setTitle($data['title']);
            }

        }
        if (array_key_exists('content', $data)) {
            if ($journal->getContent() !== $data['content']) {
                $journal->setContent($data['content']);
            }
        }
        $this->dailyJournalRepository->save($journal);

        return $journal->serialize();
    }

    /**
     * @throws \Exception
     */
    public function getJournalsByDate(array $data, int $id): array
    {
        $journal = $this->dailyJournalRepository->getDailyJournalsByDate($this->dateFormat($data['created_at']), $id);

        return $journal === null ? [] : $journal->serialize();
    }

    public function getDatesAndScores(int $editor_id): array
    {
        $journals = $this->dailyJournalRepository->findBy(['editor' => $editor_id]);

        $datesAndScores = [];
        if ($journals === null) {
            return ["no_journals"=>true];
        }
        foreach ($journals as $journal) {
            $datesAndScores[] = [
                "date" => $journal->getCreatedAt()->format('Y-m-d'),
                "score" => $journal->getSentimentScore()
            ];
        }

        return $datesAndScores;
    }


    /**
     * @throws \Exception
     */
    private function dateFormat(string $date): \DateTimeInterface
    {
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $date);
        $dateTime->setTime(0, 0, 0, 0);
        if ($dateTime === false) {
            throw new \InvalidArgumentException('Invalid date format');
        }
        return $dateTime;
    }
}