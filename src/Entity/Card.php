<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
{
    const COLORS = ['RED', 'GREEN', 'BLUE', 'BLACK'];
    const NUMBERS = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Deck::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deck;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeck(): ?Deck
    {
        return $this->deck;
    }

    public function setDeck(?Deck $deck): self
    {
        $this->deck = $deck;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        if (!in_array($color, self::COLORS)) {
            throw new \InvalidArgumentException(
                "Color must be 'RED', 'GREEN', 'BLUE' or 'BLACK'"
            );
        }

        $this->color = $color;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        if (!in_array($number, self::NUMBERS)) {
            throw new \InvalidArgumentException(
                "Number must be 1, 2, 3, 4, 5, 6, 7, 8 or 9"
            );
        }

        $this->number = $number;

        return $this;
    }
}
