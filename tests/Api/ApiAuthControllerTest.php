<?php

namespace App\Tests\Api;

use App\Entity\User;
use App\Tests\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthControllerTest extends AbstractController
{
    const URI = '/api/auth/register';

    public function test_a_player_can_register_and_is_created_and_is_redirect_to_login()
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
            self::URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($data)
        );

        $user = $this->manager
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);
        // user is created with player role
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals(['ROLE_PLAYER', 'ROLE_USER'], $user->getRoles());
        //user is redirected and logged
        $this->assertSame(Response::HTTP_TEMPORARY_REDIRECT, $this->client->getResponse()->getStatusCode());
        $this->assertSame(
            "/api/auth/login?username=$username&password=$password",
            urldecode($this->client->getResponse()->headers->get('Location'))
        );
    }

    public function test_a_player_can_not_register_with_an_empty_body()
    {
        $data = [];

        $this->client->request(
            'POST',
            self::URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($data)
        );

        $response = $this->client->getResponse();

        // user is not created
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function test_a_player_can_not_register_without_username()
    {
        $username = '';
        $password = $this->faker->password;
        $email = $this->faker->email;

        $data = [
            "username" => $username,
            "password" => $password,
            "email" => $email,
        ];

        $this->client->request(
            'POST',
            self::URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($data)
        );

        $response = $this->client->getResponse();

        $user = $this->manager
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);
        // user is not created
        $this->assertEquals(null, $user);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function test_a_player_can_not_register_without_password()
    {
        $username = $this->faker->userName;
        $password = '';
        $email = $this->faker->email;

        $data = [
            "username" => $username,
            "password" => $password,
            "email" => $email,
        ];

        $this->client->request(
            'POST',
            self::URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($data)
        );

        $response = $this->client->getResponse();

        $user = $this->manager
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);
        // user is not created
        $this->assertEquals(null, $user);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function test_a_player_can_not_register_without_email()
    {
        $username = $this->faker->userName;
        $password = $this->faker->password;

        $data = [
            "username" => $username,
            "password" => $password,
            "email" => 'testNotEmail',
        ];

        $this->client->request(
            'POST',
            self::URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($data)
        );

        $response = $this->client->getResponse();

        $user = $this->manager
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);
        // user is not created
        $this->assertEquals(null, $user);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}