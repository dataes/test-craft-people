<?php

namespace App\EventListener;

use App\Event\DeckServiceEvent;

/**
 * Class DeckServiceListener
 * @package App\EventListener
 */
class DeckServiceListener
{
    const INITIALIZE = 'deck.initialize.event';
    const CARD_TAKEN = 'deck.card.taken.event';

    /**
     * @param DeckServiceEvent $event
     */
    public function onInitializeDeck(DeckServiceEvent $event)
    {
        $event->getDeckService()->initializeDeck($event->getUser());
    }

    /**
     * @param DeckServiceEvent $event
     */
    public function onCardTaken(DeckServiceEvent $event)
    {
        $event->setCard($event->getDeckService()->pickRandomCard($event->getUser()->getDeck()));
    }
}