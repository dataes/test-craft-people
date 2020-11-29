<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\Deck;
use App\Entity\User;
use App\Repository\CardRepositoryInterface;
use App\Repository\DeckRepositoryInterface;
use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class DeckService
 * @package App\Service
 */
class DeckService
{
    /**
     * @var DeckRepositoryInterface
     */
    private $deckRepository;

    /**
     * @var CardRepositoryInterface
     */
    private $cardRepository;

    /**
     * DeckService constructor.
     * @param DeckRepositoryInterface $deckRepository
     */
    public function __construct(DeckRepositoryInterface $deckRepository, CardRepositoryInterface $cardRepository)
    {
        $this->deckRepository = $deckRepository;
        $this->cardRepository = $cardRepository;
    }

    /**
     * @param User $user
     * @return Deck
     */
    public function initializeDeck(User $user): Deck
    {
        $userDeck = $user->getDeck();

        if ($userDeck) {
            $this->deckRepository->remove($userDeck);
        }

        $deck = Deck::create($this->createCards());

        $deck->setUser($user);

        return $this->deckRepository->save($deck);
    }

    /**
     * @param Deck $deck
     * @return Card
     */
    public function pickRandomCard(Deck $deck): Card
    {
        $cards = $deck->getCards()->toArray();

        $cardPicked = $cards[array_rand($cards)];

        return $this->cardRepository->remove($cardPicked);
    }

    /**
     * Create a set of cards for a deck
     *
     * @return ArrayCollection
     */
    private function createCards(): ArrayCollection
    {
        $cardNumbers = CardRepository::NUMBERS;

        $cards = new ArrayCollection();

        foreach (CardRepository::COLORS as $color) {
            for ($number = reset($cardNumbers); $number <= end($cardNumbers); $number++) {
                $cards[] = Card::create($color, $number);
            }
        }

        return $cards;
    }
}