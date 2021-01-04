<?php

namespace App\Repository;

use App\Entity\Deck;

interface DeckRepositoryInterface
{
    /**
     * @param Deck $deck
     * @return Deck
     */
    public function save(Deck $deck);

    /**
     * @param Deck $deck
     * @return Deck
     */
    public function remove(Deck $card);
}