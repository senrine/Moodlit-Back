<?php

namespace App\Entity;

use App\Repository\RapportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RapportRepository::class)]
class Rapport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private mixed $most_used_word = null;

    #[ORM\Column]
    private ?int $average_sentiment = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $happiest_day = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $editor = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMostUsedWord(): mixed
    {
        return $this->most_used_word;
    }

    public function setMostUsedWord(mixed $most_used_word): static
    {
        $this->most_used_word = $most_used_word;

        return $this;
    }

    public function getAverageSentiment(): ?int
    {
        return $this->average_sentiment;
    }

    public function setAverageSentiment(int $average_sentiment): static
    {
        $this->average_sentiment = $average_sentiment;

        return $this;
    }

    public function getHappiestDay(): ?\DateTimeInterface
    {
        return $this->happiest_day;
    }

    public function setHappiestDay(\DateTimeInterface $happiest_day): static
    {
        $this->happiest_day = $happiest_day;

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
    public function serialize() : array
    {
        return [
            'id' => $this->getId(),
            'created_at' => $this->getCreatedAt(),
            'most_used_word' => $this->getMostUsedWord(),
            'average_sentiment' => $this->getAverageSentiment(),
            'happiest_day' => $this->getHappiestDay(),
            'editor' => $this->getEditor()->serialize(),
        ];
    }
}
