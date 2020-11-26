<?php

namespace App\Tests\Entities;

use App\Entity\Deck;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_a_user_can_set_and_get_his_deck()
    {
        $user = new User();
        $user->setDeck(new Deck());
        $this->assertInstanceOf(Deck::class, $user->getDeck());
    }
}