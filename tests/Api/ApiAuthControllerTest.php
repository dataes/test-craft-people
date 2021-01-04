<?php

namespace App\Tests\Api;

use App\Entity\User;
use App\Tests\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthControllerTest extends AbstractController
{
    const REGISTER_URI = '/api/auth/register';
    const LOGIN_URI = '/api/auth/login';

    // register() functional tests

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
            self::REGISTER_URI, [], [],
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
            self::REGISTER_URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($data)
        );

        $response = $this->client->getResponse();

        // user is not created
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function test_a_player_can_not_register_two_times()
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
            self::REGISTER_URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($data)
        );

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
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
            self::REGISTER_URI, [], [],
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
            self::REGISTER_URI, [], [],
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
            self::REGISTER_URI, [], [],
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
    
    // login() functional tests
    
    public function test_a_player_can_not_login_if_is_not_registered()
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
            self::LOGIN_URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($data)
        );

        $response = $this->client->getResponse();

        $user = $this->manager
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);

        $this->assertEquals(null, $user);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function test_a_player_can_login_if_is_registered_and_receive_a_new_token()
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

        // now user try to login after been registered
        $this->client->request(
            'POST',
            self::LOGIN_URI, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($data)
        );
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        // user receive a new token upon login
        $this->assertNotEmpty(json_decode($this->client->getResponse()->getContent())->token);
    }

}