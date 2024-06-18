<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $undergroundCard = null;

    #[ORM\Column(length: 50)]
    private ?string $symbol = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isUndergroundCard(): ?bool
    {
        return $this->undergroundCard;
    }

    public function setUndergroundCard(bool $undergroundCard): static
    {
        $this->undergroundCard = $undergroundCard;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): static
    {
        $this->symbol = $symbol;

        return $this;
    }
}
