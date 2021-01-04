<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Deck::class, inversedBy="cards", cascade="persist")
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
        if (!in_array($color, CardRepository::COLORS)) {
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
        if (!in_array($number, CardRepository::NUMBERS)) {
            throw new DomainException(
                "Number not allowed"
            );
        }

        $this->number = $number;

        return $this;
    }
}
