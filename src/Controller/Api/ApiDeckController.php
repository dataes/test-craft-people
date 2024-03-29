<?php

namespace App\Controller\Api;

use App\Event\DeckServiceEvent;
use App\EventListener\DeckServiceListener;
use Exception;
use App\Service\DeckService;
use App\Security\UserVoter;
use FOS\UserBundle\Model\UserManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/deck")
 */
class ApiDeckController extends AbstractController
{
    /**
     * @var DeckService
     */
    private $deckService;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * ApiDeckController constructor.
     * @param DeckService $deckService
     */
    public function __construct(DeckService $deckService)
    {
        $this->deckService = $deckService;
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addListener(DeckServiceListener::INITIALIZE, [new DeckServiceListener(), 'onInitializeDeck']);
        $this->dispatcher->addListener(DeckServiceListener::CARD_TAKEN, [new DeckServiceListener(), 'onCardTaken']);
    }

    /**
     * @Route("/init/{player_id}", name="api_deck_init",  methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Initialize a deck for a specific new player. (Role 'system' granted only)"
     * )
     * @SWG\Parameter(
     *     name="player_id",
     *     in="path",
     *     type="integer",
     *     required=true,
     *     format="int64",
     * )
     * @SWG\Tag(name="Initialize a deck")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse
     */
    public function initialize(Request $request, UserManagerInterface $userManager): JsonResponse
    {
        $user = $userManager->findUserBy(['id' => $request->get('player_id')]);

        if (is_null($user)) {
            $data = [
                "message" => "User doesn't exist",
                "links" => [
                    "href" => "http://localhost:8000/api/doc.json",
                ],
            ];

            return new JsonResponse($data, Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->denyAccessUnlessGranted(UserVoter::CAN_INITIALIZE_DECK, $user);
        } catch (Exception $e) {
            $data = [
                "message" => "As a player, you can not initialize a deck.",
                "links" => [
                    "href" => "http://localhost:8000/api/doc.json",
                ],
            ];

            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        if (!is_null($user->getDeck())) {
            $data = [
                "message" => "The player has already a deck",
                "links" => [
                    "href" => "http://localhost:8000/api/doc.json",
                ],
            ];

            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        try {
            $this->dispatcher->dispatch(
                new DeckServiceEvent($this->deckService, $user),
                DeckServiceListener::INITIALIZE
            );
        } catch (Exception $e) {
            $data = [
                "message" => $e->getMessage(),
            ];

            return new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        $data = [
            "message" => "A new deck has been initialized for " . $user->getUsername(),
            "links" => [
                "href" => "http://localhost:8000/api/deck/pick/card",
                "rel" => "pick",
                "type" => "GET",
            ],
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/pick/card", name="api_pick_card",  methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Pick a card from your given deck"
     * )
     *
     * @SWG\Tag(name="Pick a card")
     * @Security(name="Bearer")
     *
     * @return JsonResponse
     */
    public function pick(): JsonResponse
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $deck = $user->getDeck();

        if (is_null($deck)) {
            $data = [
                "message" => "Sorry you don't have any deck initialized.",
                "links" => [
                    "href" => "http://localhost:8000/api/deck/init/$userId",
                    "rel" => "initialize",
                    "type" => "POST",
                ],
            ];

            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $cardsLeft = $deck->getCards()->count() - 1;

        try {
            $listener = new DeckServiceEvent($this->deckService, $user);
            $this->dispatcher->dispatch(
                $listener,
                DeckServiceListener::CARD_TAKEN
            );
            $card = $listener->getCard();
        } catch (Exception $e) {
            $data = [
                "message" => $e->getMessage(),
            ];

            return new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $cardNumber = $card->getNumber();
        $cardColor = $card->getColor();

        $data = [
            "message" => "You picked the [$cardColor $cardNumber] card, ($cardsLeft left)",
            "links" => [
                "href" => "http://localhost:8000/api/doc.json",
            ],
        ];

        // Initialize a new deck if no cards anymore
        if ($cardsLeft === 0) {
            $this->dispatcher->dispatch(
                new DeckServiceEvent($this->deckService, $user),
                DeckServiceListener::INITIALIZE
            );
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
