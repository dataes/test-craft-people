<?php

namespace App\Tests\Api;

use App\Entity\User;
use App\Tests\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthControllerTest extends AbstractController
{
    const URI = '/api/auth/register';

    public function test_a_player_can_register_is_created_and_is_redirect_to_login()
    {
        $username = $this->faker->userName;
        $password = $this->faker->userName; // todo put password, fix encoding issue with assertResponseRedirects()
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
        $this->assertResponseRedirects(
            "/api/auth/login?username=$username&password=$password",
            Response::HTTP_TEMPORARY_REDIRECT
        );

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
        $this->assertEquals( null, $user);
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
        $email = $this->faker->email;

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


//        $users = $this->loadFixture(new UsersFixtures());


    // register() unit tests

//    public function test_supports_return_false_if_attribute_is_not_supported()
//    {
//        $subject = new User();
//        $attribute = $this->faker->name;
//
//        $reflectedMethod = $this->reflectSupportsMethod();
//
//        $this->assertEquals(
//            false,
//            $reflectedMethod->invokeArgs(
//                $this->userVoter,
//                [$attribute, $subject]
//            )
//        );
//    }


//    // reflected Protected Methods
//
//    /**
//     * @return ReflectionMethod
//     */
//    private function reflectSupportsMethod(): ReflectionMethod
//    {
////        $reflectedMethod = new ReflectionMethod(
////            UserVoter::class,
////            'supports'
////        );
////        $reflectedMethod->setAccessible(true);
////
////        return $reflectedMethod;
//    }
}