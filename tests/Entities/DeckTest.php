<?php

namespace App\Tests\Entities;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Card;
use App\Entity\Deck;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase
{

    public function test_a_deck_can_add_and_get_and_remove_cards()
    {
        $deck = new Deck();
        $deck->addCard(Card::create('RED', 1));
        $this->assertInstanceOf(Card::class, $deck->getCards()->first());
        $deck->removeCard($deck->getCards()->first());
        $this->assertEmpty($deck->getCards());
    }

    public function test_a_deck_can_set_and_get_his_user_owner()
    {
        $deck = new Deck();
        $deck->setUser(new User());
        $this->assertInstanceOf(User::class, $deck->getUser());
    }
}