<?php

namespace App\Repository;

use App\Entity\Card;

interface CardRepositoryInterface
{
    // Adding in const will result in new kind of cards when a deck is created
    const COLORS = ['RED', 'GREEN', 'BLUE', 'BLACK'];
    const NUMBERS = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    /**
     * @param Card $card
     * @return Card
     */
    public function remove(Card $card);
}