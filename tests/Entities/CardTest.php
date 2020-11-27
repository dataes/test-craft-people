<?php

namespace App\Tests\Entities;

use App\Entity\Card;
use App\Entity\Deck;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    public function test_a_card_can_set_and_get_his_deck()
    {
        $card = Card::create('RED', 1);
        $card->setDeck(Deck::create(new ArrayCollection([$card])));
        $this->assertInstanceOf(Deck::class, $card->getDeck());
    }

    public function test_a_card_can_set_and_get_his_color()
    {
        $card =  Card::create('BLACK', 2);
        $this->assertEquals('BLACK', $card->getColor());
    }

    public function test_a_card_can_set_and_get_his_number()
    {
        $card =  Card::create('BLACK', 1);
        $this->assertEquals(1, $card->getNumber());
    }

    public function test_a_card_can_not_set_his_color_if_color_is_not_allowed()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Color must be 'RED', 'GREEN', 'BLUE' or 'BLACK'");
        $card =  Card::create('PINK', 4);
        $card->setColor('PINK');
    }

    public function test_a_card_can_not_set_his_number_if_number_is_not_allowed()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Number must be 1, 2, 3, 4, 5, 6, 7, 8 or 9");
        $card =  Card::create('BLUE', 10);
        $card->setNumber(10);
    }
}