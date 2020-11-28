<?php

namespace App\Tests\Service;

use App\Entity\Card;
use App\Entity\Deck;
use App\Entity\User;
use App\Repository\CardRepository;
use App\Repository\CardRepositoryInterface;
use App\Repository\DeckRepositoryInterface;
use App\Service\DeckService;
use Doctrine\Common\Collections\ArrayCollection;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class DeckServiceTest extends TestCase
{
    protected $faker;
    protected $deckService;
    protected $deckRepository;
    protected $cardRepository;
    protected $deck;

    public function setUp()
    {
        $this->faker = Factory::create();
        $this->deckRepository = $this->createMock(DeckRepositoryInterface::class);
        $this->cardRepository = $this->createMock(CardRepositoryInterface::class);
        $this->deck = $this->createMock(Deck::class);
        $this->deckService = new DeckService(
            $this->deckRepository,
            $this->cardRepository
        );
    }

    // createCards() unit tests

    public function test_create_cards_can_create_a_new_collection_of_cards()
    {
        $reflectedMethod = $this->reflectCreateCardsMethod();

        $result = $reflectedMethod->invokeArgs(
            $this->deckService,
            []
        );

        $expectedCount = count(CardRepository::NUMBERS) * count(CardRepository::COLORS);

        $this->assertCount($expectedCount, $result);
        $this->assertInstanceOf(ArrayCollection::class, $result);
        $this->assertInstanceOf(Card::class, $result->first());
    }

    // initializeDeck() unit tests

    public function test_initialize_deck_can_create_a_new_deck_with_cards_for_a_user()
    {
        $user = new User();
        $cards = new ArrayCollection();
        $deck = Deck::create($cards);

        $this->deckRepository->expects($this->never())
            ->method('remove')
            ->willReturn($deck);

        $this->deckRepository->expects($this->once())
            ->method('save')
            ->willReturn($deck);

        $result = $this->deckService->initializeDeck($user);

        $this->assertInstanceOf(Deck::class, $result);
        $this->assertNotEmpty($user->getDeck()->getCards());
    }

    public function test_initialize_deck_remove_an_existent_deck_and_create_a_new_one_with_cards()
    {
        $user = new User();
        $cards = new ArrayCollection();
        $deck = Deck::create($cards);
        $user->setDeck($deck);

        $this->deckRepository->expects($this->once())
            ->method('remove')
            ->willReturn($deck);

        $this->deckRepository->expects($this->once())
            ->method('save')
            ->willReturn($deck);

        $result = $this->deckService->initializeDeck($user);

        $this->assertInstanceOf(Deck::class, $result);
        $this->assertNotEmpty($user->getDeck()->getCards());
    }

    // pickRandomCard() unit tests

//    public function test_pick_random_cards_can_pick_a_random_card()
//    {
        // TODO

//        $user = new User();
//        $card1 = Card::create(CardRepository::COLORS[0], CardRepository::NUMBERS[0]);
//        $card2 = Card::create(CardRepository::COLORS[1], CardRepository::NUMBERS[1]);
//        $card3 = Card::create(CardRepository::COLORS[2], CardRepository::NUMBERS[2]);
//        $card4 = Card::create(CardRepository::COLORS[3], CardRepository::NUMBERS[3]);
//        $cards = new ArrayCollection([
//            $card1,
//            $card2,
//            $card3,
//            $card4,
//        ]);
//
//        $deck = Deck::create($cards);
//        $user->setDeck($deck);
//
//        $randomCard = $cards[array_rand($cards->toArray())];
//
//        $this->deck->expects($this->at(0))
//            ->method('getCards')
//            ->willReturn($cards);
//
//        $this->cardRepository->expects($this->once())
//            ->method('remove')
//            ->with($randomCard)
//            ->willReturn($randomCard);
//
//        $result = $this->deckService->pickRandomCard($deck);
//        $this->assertInstanceOf(Card::class, $result);
//        $this->assertCount(3, count($cards));
//    }

    // reflected Protected Methods

    /**
     * @return ReflectionMethod
     */
    private function reflectCreateCardsMethod(): ReflectionMethod
    {
        $reflectedMethod = new ReflectionMethod(
            DeckService::class,
            'createCards'
        );
        $reflectedMethod->setAccessible(true);

        return $reflectedMethod;
    }

}