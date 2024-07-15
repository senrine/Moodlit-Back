<?php

namespace App\Entity;

use App\Repository\DailyJournalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DailyJournalRepository::class)]
class DailyJournal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $editor = null;


    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $sentiment_score = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getEditor(): ?User
    {
        return $this->editor;
    }

    public function setEditor(?User $editor): static
    {
        $this->editor = $editor;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getSentimentScore(): ?int
    {
        return $this->sentiment_score;
    }

    public function setSentimentScore(int $sentiment_score): static
    {
        $this->sentiment_score = $sentiment_score;

        return $this;
    }

    public function serialize(): array
    {
        return [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "created_at" => $this->getCreatedAt(),
            "editor" => $this->getEditor()->serialize(),
            "content" => $this->getContent(),
            "sentiment_score" => $this->getSentimentScore()
        ];
    }
}
