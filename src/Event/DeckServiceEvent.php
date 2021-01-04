<?php

namespace App\Event;

use App\Entity\Card;
use App\Entity\User;
use App\Service\DeckService;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class DeckServiceEvent
 * @package App\Event
 */
class DeckServiceEvent extends Event
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var DeckService
     */
    private $deckService;
    /**
     * @var Card
     */
    private $card;

    /**
     * DeckServiceEvent constructor.
     * @param DeckService $deckService
     * @param User $user
     */
    public function __construct(DeckService $deckService, User $user)
    {
        $this->user = $user;
        $this->deckService = $deckService;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return DeckService
     */
    public function getDeckService(): DeckService
    {
        return $this->deckService;
    }

    /**
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }

    /**
     * @param Card $card
     */
    public function setCard(Card $card): void
    {
        $this->card = $card;
    }


}