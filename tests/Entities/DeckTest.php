<?php

namespace App\Tests\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Card;
use App\Entity\Deck;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase
{

    public function test_a_deck_can_add_and_get_and_remove_cards()
    {
        $cards = [
            Card::create('RED', 1),
            Card::create('BLACK', 2),
        ];
        $deck = Deck::create(new ArrayCollection($cards));
        $deck->addCard(Card::create('BLUE', 1));
        $this->assertInstanceOf(Card::class, $deck->getCards()->first());
        $this->assertCount(3, $deck->getCards());
        $deck->removeCard($deck->getCards()->first());
        $this->assertCount(2, $deck->getCards());
    }

    public function test_a_deck_can_set_and_get_his_user_owner()
    {
        $deck = Deck::create(new ArrayCollection([Card::create('RED', 1)]));
        $deck->setUser(new User());
        $this->assertInstanceOf(User::class, $deck->getUser());
    }
}