<?php

namespace App\Tests\Api;

use App\Entity\Deck;
use App\Entity\User;
use App\Security\UserVoter;
use App\Tests\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiDeckControllerTest extends AbstractController
{
    const INITIALIZE_URI = '/api/deck/init/';
    const PICK_CARD_URI = '/api/deck/pick/card';
    const REGISTER_URI = '/api/auth/register';
    const LOGIN_URI = '/api/auth/login';

    protected $token;

    // initialize() functional tests

    public function test_a_system_user_can_initialize_a_deck_for_a_player()
    {
        $user = $this->registerAs(UserVoter::ROLE_SYSTEM);

        $this->client->request(
            'POST',
            self::INITIALIZE_URI . $user->getId(), [], [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer '. $this->token
            ]
        );

        $deck = $this->manager
            ->getRepository(Deck::class)
            ->findAll()[0];
        $this->assertSame($user->getId(), $deck->getUser()->getId());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function test_a_player_user_can_not_initialize_a_deck()
    {
        $user = $this->registerAs(UserVoter::ROLE_PLAYER);

        $this->client->request(
            'POST',
            self::INITIALIZE_URI . $user->getId(), [], [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer '. $this->token
            ]
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function test_can_not_initialize_a_deck_for_an_unknown_user()
    {
        $this->registerAs(UserVoter::ROLE_SYSTEM);

        $this->client->request(
            'POST',
            self::INITIALIZE_URI . '99', [], [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer '. $this->token
            ]
        );

        $this->assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function test_can_not_initialize_a_deck_if_deck_already_exist()
    {
        $user = $this->registerAs(UserVoter::ROLE_SYSTEM);

        $this->client->request(
            'POST',
            self::INITIALIZE_URI . $user->getId(), [], [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer '. $this->token
            ]
        );
        $this->client->request(
            'POST',
            self::INITIALIZE_URI . $user->getId(), [], [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer '. $this->token
            ]
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    // pick() functional tests

    public function test_a_user_can_pick_a_card_in_his_deck()
    {
        $user = $this->registerAs(UserVoter::ROLE_SYSTEM);

        $this->client->request(
            'POST',
            self::INITIALIZE_URI . $user->getId(), [], [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer '. $this->token
            ]
        );

        $this->client->request(
            'GET',
            self::PICK_CARD_URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer '. $this->token
            ]
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function test_a_user_can_not_pick_a_card_in_his_deck_if_not_initialized()
    {
        $this->registerAs(UserVoter::ROLE_SYSTEM);

        $this->client->request(
            'GET',
            self::PICK_CARD_URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer '. $this->token
            ]
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @param $role
     * @return object
     */
    private function registerAs($role): User
    {
        $username = $this->faker->userName;
        $password = $this->faker->password;
        $email = $this->faker->email;

        $data = [
            "username" => $username,
            "password" => $password,
            "email" => $email,
        ];

        $this->client->request(
            'POST',
            self::REGISTER_URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($data)
        );

        $this->client->request(
            'POST',
            self::LOGIN_URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($data)
        );

        $this->token = json_decode($this->client->getResponse()->getContent())->token;

        $user = $this->manager
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);

        $user->setRoles([$role]);
        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }

}