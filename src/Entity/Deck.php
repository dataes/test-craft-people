<?php

namespace App\Entity;

use App\Repository\DeckRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;

/**
 * @ORM\Entity(repositoryClass=DeckRepository::class)
 */
class Deck
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="deck", orphanRemoval=true, cascade="persist")
     */
    private $cards;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="deck", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * Deck constructor.
     */
    private function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    /**
     * @param ArrayCollection $cards
     * @return Deck
     */
    public static function create(ArrayCollection $cards): Deck
    {
        $deck = new Deck();
        foreach ($cards as $card) {
            $deck->addCard($card);
        }

        return $deck;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    /**
     * @param Card $card
     * @return $this
     */
    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setDeck($this);
        }

        return $this;
    }

    /**
     * @param Card $card
     * @return $this
     */
    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getDeck() === $this) {
                $card->setDeck(null);
            }
        }

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param UserInterface|null $user
     * @return $this
     */
    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newDeck = null === $user ? null : $this;
        if ($user->getDeck() !== $newDeck) {
            $user->setDeck($newDeck);
        }

        return $this;
    }
}
