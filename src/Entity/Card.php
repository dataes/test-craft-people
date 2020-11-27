<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
final class Card
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

    /**
     * Card constructor.
     */
    private function __construct(){}

    /**
     * @param string $color
     * @param int $number
     * @return Card
     */
    public static function create(string $color, int $number) : Card
    {
        $card = new Card();
        $card->setColor($color);
        $card->setNumber($number);
        return $card;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Deck|null
     */
    public function getDeck(): ?Deck
    {
        return $this->deck;
    }

    /**
     * @param Deck|null $deck
     * @return $this
     */
    public function setDeck(?Deck $deck): self
    {
        $this->deck = $deck;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function setColor(string $color): self
    {
        if (!in_array($color, self::COLORS)) {
            throw new DomainException(
                "Color must be 'RED', 'GREEN', 'BLUE' or 'BLACK'"
            );
        }

        $this->color = $color;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return $this
     */
    public function setNumber(int $number): self
    {
        if (!in_array($number, self::NUMBERS)) {
            throw new DomainException(
                "Number must be 1, 2, 3, 4, 5, 6, 7, 8 or 9"
            );
        }

        $this->number = $number;

        return $this;
    }
}
